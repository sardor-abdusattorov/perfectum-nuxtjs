<?php

declare(strict_types=1);

use App\Filament\Support\RichContentStateCast;
use Filament\Forms\Components\RichEditor\RichContentRenderer;

function richDocument(string $html): array
{
    return (new RichContentStateCast)->set(
        RichContentRenderer::make()->getEditor()->setContent($html)->getDocument()
    );
}

/**
 * @param  array<string, mixed>  $node
 * @return array<int, string>
 */
function looseRichNodes(array $node, string $trail = ''): array
{
    $inline = ['text', 'hardBreak', 'image'];
    $blocks = ['doc', 'blockquote', 'detailsContent', 'gridColumn', 'lead', 'listItem', 'tableCell', 'tableHeader'];

    $type = $node['type'] ?? '?';
    $where = $trail === '' ? $type : "{$trail}>{$type}";
    $children = is_array($node['content'] ?? null) ? $node['content'] : [];
    $found = [];

    foreach ($children as $child) {
        if (in_array($type, $blocks, true) && in_array($child['type'] ?? '', $inline, true)) {
            $found[] = $where;
        }

        $found = [...$found, ...looseRichNodes($child, $where)];
    }

    if ($type === 'listItem' && ($children[0]['type'] ?? 'paragraph') !== 'paragraph') {
        $found[] = "{$where} opens with {$children[0]['type']}";
    }

    return array_values(array_unique($found));
}

it('wraps the loose text a table cell was given', function (): void {
    $doc = richDocument('<table><tbody><tr><th>Тариф</th><td>10 000 сум</td></tr></tbody></table>');

    expect(looseRichNodes($doc))->toBe([]);

    $row = $doc['content'][0]['content'][0];
    expect($row['content'][0]['content'][0]['type'])->toBe('paragraph')
        ->and($row['content'][0]['content'][0]['content'][0]['text'])->toBe('Тариф')
        ->and($row['content'][1]['content'][0]['content'][0]['text'])->toBe('10 000 сум');
});

it('gives a list item the paragraph its schema opens with', function (): void {
    $doc = richDocument('<ul><li>Первый</li><li>Второй</li></ul>');

    expect(looseRichNodes($doc))->toBe([]);

    $item = $doc['content'][0]['content'][0];
    expect($item['content'][0]['type'])->toBe('paragraph')
        ->and($item['content'][0]['content'][0]['text'])->toBe('Первый');
});

it('leaves a document that already reads correctly alone', function (): void {
    $html = '<p>Скорость <em>которая</em> меняет</p><ul><li><p>Пункт</p></li></ul>';

    $parsed = RichContentRenderer::make()->getEditor()->setContent($html)->getDocument();

    expect((new RichContentStateCast)->set($parsed))->toBe($parsed);
});

it('keeps blocks and loose runs in the order they were written', function (): void {
    $doc = richDocument('<blockquote>До<p>Абзац</p>После</blockquote>');

    expect(looseRichNodes($doc))->toBe([])
        ->and(array_column($doc['content'][0]['content'], 'type'))->toBe(['paragraph', 'paragraph', 'paragraph'])
        ->and($doc['content'][0]['content'][0]['content'][0]['text'])->toBe('До')
        ->and($doc['content'][0]['content'][1]['content'][0]['text'])->toBe('Абзац')
        ->and($doc['content'][0]['content'][2]['content'][0]['text'])->toBe('После');
});

it('hands anything that is not a document straight back', function (): void {
    expect((new RichContentStateCast)->set(null))->toBeNull()
        ->and((new RichContentStateCast)->set('<p>текст</p>'))->toBe('<p>текст</p>')
        ->and((new RichContentStateCast)->get(['type' => 'doc']))->toBe(['type' => 'doc']);
});
