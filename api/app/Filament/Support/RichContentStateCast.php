<?php

declare(strict_types=1);

namespace App\Filament\Support;

use Filament\Schemas\Components\StateCasts\Contracts\StateCast;

/**
 * The PHP side of TipTap parses HTML literally: `<td>текст</td>` becomes a
 * table cell holding a bare string, and `<li>текст</li>` a list item holding
 * one. The editor in the browser demands a block there, and a document that
 * breaks its own schema takes ProseMirror down on the first click — the field
 * throws «Called contentMatchAt on a node with invalid content» and empties
 * itself. Most of the content carried over from the old site is written that
 * way, so the loose runs are wrapped on the way into the form.
 */
class RichContentStateCast implements StateCast
{
    /** @var array<int, string> */
    private const INLINE = ['text', 'hardBreak', 'image'];

    /**
     * Nodes whose children the editor's schema declares as `block+`.
     *
     * @var array<int, string>
     */
    private const BLOCKS_ONLY = [
        'doc',
        'blockquote',
        'detailsContent',
        'gridColumn',
        'lead',
        'listItem',
        'tableCell',
        'tableHeader',
    ];

    public function get(mixed $state): mixed
    {
        return $state;
    }

    public function set(mixed $state): mixed
    {
        return is_array($state) ? $this->node($state) : $state;
    }

    /**
     * @param  array<string, mixed>  $node
     * @return array<string, mixed>
     */
    private function node(array $node): array
    {
        if (! is_array($node['content'] ?? null)) {
            return $node;
        }

        $node['content'] = array_map(
            fn (mixed $child): mixed => is_array($child) ? $this->node($child) : $child,
            $node['content'],
        );

        if (in_array($node['type'] ?? '', self::BLOCKS_ONLY, true)) {
            $node['content'] = $this->wrap($node['content']);
        }

        /** A list item is `paragraph block*`: a nested list cannot open it. */
        if (($node['type'] ?? '') === 'listItem' && ($node['content'][0]['type'] ?? 'paragraph') !== 'paragraph') {
            array_unshift($node['content'], ['type' => 'paragraph']);
        }

        return $node;
    }

    /**
     * @param  array<int, mixed>  $children
     * @return array<int, mixed>
     */
    private function wrap(array $children): array
    {
        $wrapped = [];
        $loose = [];

        foreach ($children as $child) {
            if (is_array($child) && ! in_array($child['type'] ?? '', self::INLINE, true)) {
                $wrapped = [...$wrapped, ...$this->paragraph($loose), $child];
                $loose = [];

                continue;
            }

            $loose[] = $child;
        }

        return [...$wrapped, ...$this->paragraph($loose)];
    }

    /**
     * @param  array<int, mixed>  $loose
     * @return array<int, array<string, mixed>>
     */
    private function paragraph(array $loose): array
    {
        return $loose === [] ? [] : [['type' => 'paragraph', 'content' => $loose]];
    }
}
