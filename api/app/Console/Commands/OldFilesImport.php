<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Action;
use App\Models\Device;
use App\Models\DeviceBrand;
use App\Models\Document;
use App\Models\News;
use App\Models\Page;
use App\Models\Service;
use App\Models\Tariff;
use App\Models\Tender;
use App\Models\Vacancy;
use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

/**
 * The imported content references the pictures and documents by the paths the
 * old site stored them under. Drop that site's storage/app/public into
 * storage/app/old_files/public (and its webroot pictures folder beside them,
 * as old_files/public/pictures) — this command carries every file over onto
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

        [$copied, $skipped, $failed] = $this->copy($source, Storage::disk('public'));

        $this->info("Скопировано: {$copied}, уже на месте: {$skipped}.");

        $this->stampDocumentSizes();

        $missing = $this->referenced()->reject(
            fn (string $path): bool => Storage::disk('public')->exists($path),
        );

        if ($missing->isEmpty()) {
            $this->info('Все файлы, на которые ссылается контент, на месте.');
        } else {
            $this->warn("Контент ссылается на {$missing->count()} файлов, которых нет:");
            $missing->take(20)->each(fn (string $path) => $this->line("  {$path}"));
        }

        if ($failed !== []) {
            $this->error('Не записались '.count($failed).' файлов, первые: '.implode(', ', array_slice($failed, 0, 5)));

            return self::FAILURE;
        }

        return self::SUCCESS;
    }

    /**
     * @return array{int, int, array<int, string>}
     */
    private function copy(string $source, Filesystem $disk): array
    {
        $copied = $skipped = 0;
        $failed = [];

        foreach (File::allFiles($source) as $file) {
            $relative = str_replace(DIRECTORY_SEPARATOR, '/', $file->getRelativePathname());

            if ($disk->exists($relative) && $disk->size($relative) === $file->getSize()) {
                $skipped++;

                continue;
            }

            $stream = fopen($file->getPathname(), 'r');
            $written = $stream !== false && $disk->writeStream($relative, $stream) !== false;

            if (is_resource($stream)) {
                fclose($stream);
            }

            $written ? $copied++ : $failed[] = $relative;
        }

        return [$copied, $skipped, $failed];
    }

    /**
     * The documents were seeded before their files arrived, so the size the
     * model normally stamps on upload is filled in here instead.
     */
    private function stampDocumentSizes(): void
    {
        Document::query()->each(function (Document $document): void {
            $sizes = [];

            foreach ($document->getTranslations('file') as $locale => $path) {
                if (filled($path) && Storage::disk('public')->exists($path)) {
                    $sizes[$locale] = Storage::disk('public')->size($path);
                }
            }

            if ($sizes !== [] && $sizes !== $document->getTranslations('size')) {
                $document->setTranslations('size', $sizes)->saveQuietly();
            }
        });
    }

    /**
     * Every path the seeded rows point at: the image columns, the files the
     * tenders and documents carry, the icons inside the tariff buttons, and
     * the /storage/... links every translation of every body holds.
     *
     * @return Collection<int, string>
     */
    private function referenced(): Collection
    {
        $paths = collect()
            ->merge(Device::query()->toBase()->pluck('image'))
            ->merge(DeviceBrand::query()->toBase()->pluck('logo'))
            ->merge(News::query()->toBase()->pluck('preview_image'))
            ->merge(News::query()->toBase()->pluck('main_image'))
            ->merge(Action::query()->toBase()->pluck('preview_image'))
            ->merge(Action::query()->toBase()->pluck('main_image'))
            ->merge(Service::query()->toBase()->pluck('icon'))
            ->merge(Service::query()->toBase()->pluck('image'))
            ->merge(Tariff::query()->toBase()->pluck('image'))
            ->merge(Tariff::query()->toBase()->pluck('modal_image'))
            ->merge(Page::query()->toBase()->pluck('image'));

        foreach (Tender::query()->get() as $tender) {
            $paths = $paths->merge($tender->files ?? []);
        }

        foreach (Document::query()->get() as $document) {
            $paths = $paths->merge(array_values($document->getTranslations('file')));
        }

        // whatever shape the buttons take, an icon is a value under that key
        foreach (Tariff::query()->toBase()->pluck('buttons') as $raw) {
            $paths = $paths->merge($this->values(json_decode((string) $raw, true), 'icon'));
        }

        foreach ([News::class, Action::class, Service::class, Tender::class, Vacancy::class, Page::class] as $model) {
            foreach ($model::query()->toBase()->pluck('content') as $raw) {
                $body = implode(' ', array_filter(
                    (array) (json_decode((string) $raw, true) ?? $raw),
                    'is_string',
                ));
                preg_match_all('~/storage/((?:images|files|pictures)/[^"\'\s<>\\\\]+)~', $body, $matches);
                $paths = $paths->merge($matches[1]);
            }
        }

        return $paths
            ->filter(fn ($path): bool => is_string($path) && $path !== '' && ! str_starts_with($path, 'http'))
            ->map(fn (string $path): string => rawurldecode($path))
            ->unique()
            ->values();
    }

    /**
     * @return array<int, string>
     */
    private function values(mixed $node, string $key): array
    {
        if (! is_array($node)) {
            return [];
        }

        $found = [];

        foreach ($node as $name => $value) {
            if ($name === $key && is_string($value)) {
                $found[] = $value;
            } elseif (is_string($value) && str_starts_with($value, '[')) {
                $found = [...$found, ...$this->values(json_decode($value, true), $key)];
            } else {
                $found = [...$found, ...$this->values($value, $key)];
            }
        }

        return $found;
    }
}
