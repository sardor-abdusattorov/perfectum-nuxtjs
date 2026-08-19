<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Action;
use App\Models\Device;
use App\Models\DeviceBrand;
use App\Models\News;
use App\Models\Service;
use App\Models\Tariff;
use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

/**
 * The imported content references the pictures and documents by the paths the
 * old site stored them under. Drop that site's storage/app/public into
 * storage/app/old_files/public and this command carries every file over onto
 * the public disk, then says which referenced files are still missing.
 */
final class OldFilesImport extends Command
{
    protected $signature = 'old-files:import
        {--source=old_files/public : folder inside storage/app holding the old site\'s public disk}';

    protected $description = 'Copy the old site\'s uploads onto the public disk';

    public function handle(): int
    {
        $source = storage_path('app/'.trim((string) $this->option('source'), '/'));

        if (! is_dir($source)) {
            $this->error("Нет папки {$source}.");
            $this->line('Скопируйте storage/app/public старого сайта в storage/app/old_files/public и запустите команду снова.');

            return self::FAILURE;
        }

        [$copied, $skipped] = $this->copy($source, Storage::disk('public'));

        $this->info("Скопировано: {$copied}, уже на месте: {$skipped}.");

        $missing = $this->referenced()->reject(
            fn (string $path): bool => Storage::disk('public')->exists($path),
        );

        if ($missing->isEmpty()) {
            $this->info('Все файлы, на которые ссылается контент, на месте.');
        } else {
            $this->warn("Контент ссылается на {$missing->count()} файлов, которых нет:");
            $missing->take(20)->each(fn (string $path) => $this->line("  {$path}"));
        }

        return self::SUCCESS;
    }

    /**
     * @return array{int, int}
     */
    private function copy(string $source, Filesystem $disk): array
    {
        $copied = $skipped = 0;

        foreach (File::allFiles($source) as $file) {
            $relative = str_replace('\\', '/', $file->getRelativePathname());

            if ($disk->exists($relative) && $disk->size($relative) === $file->getSize()) {
                $skipped++;

                continue;
            }

            $stream = fopen($file->getPathname(), 'r');
            $disk->writeStream($relative, $stream);

            if (is_resource($stream)) {
                fclose($stream);
            }

            $copied++;
        }

        return [$copied, $skipped];
    }

    /**
     * Every path the seeded rows point at: the image columns, the icons the
     * tariff buttons carry, and the /storage/... links inside the bodies.
     *
     * @return Collection<int, string>
     */
    private function referenced(): Collection
    {
        $paths = collect()
            ->merge(Device::query()->pluck('image'))
            ->merge(DeviceBrand::query()->pluck('logo'))
            ->merge(News::query()->pluck('preview_image'))
            ->merge(News::query()->pluck('main_image'))
            ->merge(Action::query()->pluck('preview_image'))
            ->merge(Action::query()->pluck('main_image'))
            ->merge(Service::query()->pluck('icon'))
            ->merge(Service::query()->pluck('image'))
            ->merge(Tariff::query()->pluck('image'))
            ->merge(Tariff::query()->pluck('modal_image'));

        foreach (Tariff::query()->pluck('buttons') as $buttons) {
            foreach ((array) $buttons as $localised) {
                $rows = is_string($localised) ? json_decode($localised, true) : $localised;

                foreach ((array) $rows as $button) {
                    $paths->push(is_array($button) ? ($button['icon'] ?? null) : null);
                }
            }
        }

        foreach ([News::class, Action::class, Service::class] as $model) {
            foreach ($model::query()->pluck('content') as $content) {
                $body = is_string($content) ? $content : json_encode($content);
                preg_match_all('~/storage/((?:images|files)/[\w.\-]+)~', (string) $body, $matches);
                $paths = $paths->merge($matches[1]);
            }
        }

        return $paths
            ->filter(fn ($path): bool => is_string($path) && $path !== '' && ! str_starts_with($path, 'http'))
            ->unique()
            ->values();
    }
}
