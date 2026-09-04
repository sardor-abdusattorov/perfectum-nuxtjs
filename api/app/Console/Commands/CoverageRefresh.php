<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\CoverageLayer;
use Illuminate\Console\Command;

/**
 * Coverage shapes are read out of the uploaded archive once, when the file is
 * attached, so a layer already in place keeps whatever the reader produced back
 * then. This puts every layer through the reader again — the same path an
 * upload takes — and reports what the answer now weighs.
 */
final class CoverageRefresh extends Command
{
    protected $signature = 'coverage:refresh {key? : only this layer}';

    protected $description = 'Read the coverage archives again and store the shapes anew';

    public function handle(): int
    {
        $layers = CoverageLayer::query()
            ->when($this->argument('key'), fn ($query, $key) => $query->where('key', $key))
            ->orderBy('sort')
            ->get();

        if ($layers->isEmpty()) {
            $this->warn('Слоёв не нашлось.');

            return self::SUCCESS;
        }

        foreach ($layers as $layer) {
            $before = $this->weigh($layer);

            if (! $layer->refreshShapes()) {
                $this->warn("{$layer->key}: архив не читается, оставил как было.");

                continue;
            }

            $after = $this->weigh($layer->refresh());

            $this->line(sprintf(
                '%s: %s → %s (%s), контуров %d',
                $layer->key,
                $this->size($before),
                $this->size($after),
                $before > 0 ? sprintf('%+d%%', (int) round(($after - $before) / $before * 100)) : 'было пусто',
                $layer->features,
            ));
        }

        return self::SUCCESS;
    }

    private function weigh(CoverageLayer $layer): int
    {
        return strlen((string) $layer->getRawOriginal('geojson'));
    }

    private function size(int $bytes): string
    {
        return $bytes >= 1048576
            ? sprintf('%.2f МБ', $bytes / 1048576)
            : sprintf('%.0f КБ', $bytes / 1024);
    }
}
