<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Models\Document;
use App\Models\DocumentCategory;
use Illuminate\Http\JsonResponse;

class DocumentController
{
    public function __invoke(): JsonResponse
    {
        $documents = Document::query()
            ->published()
            ->with('category')
            ->ordered()
            ->get()
            ->groupBy(fn (Document $document): string => (string) $document->category_id);

        $groups = DocumentCategory::query()
            ->published()
            ->ordered()
            ->get()
            ->map(fn (DocumentCategory $category): array => [
                'id' => $category->id,
                'name' => $category->name,
                'documents' => self::documents($documents->get((string) $category->id)),
            ])
            ->filter(fn (array $group): bool => $group['documents'] !== [])
            ->values()
            ->all();

        if ($loose = self::documents($documents->get(''))) {
            $groups[] = ['id' => null, 'name' => null, 'documents' => $loose];
        }

        return response()->json(['data' => $groups]);
    }

    /**
     * @param  iterable<Document>|null  $documents
     * @return array<int, array<string, mixed>>
     */
    private static function documents(mixed $documents): array
    {
        return collect($documents)
            ->map(function (Document $document): array {
                $files = collect($document->getTranslations('file'))
                    ->map(fn (string $path, string $locale): array => [
                        'language' => $locale,
                        'url' => $document->url($locale),
                        'size' => $document->readableSize($locale),
                    ])
                    ->filter(fn (array $file): bool => $file['url'] !== null)
                    ->values()
                    ->all();

                return [
                    'id' => $document->id,
                    'name' => $document->name,
                    'url' => $document->url() ?? ($files[0]['url'] ?? null),
                    'size' => $document->readableSize() ?? ($files[0]['size'] ?? null),
                    'downloadable' => $document->is_downloadable,
                    'files' => $files,
                ];
            })
            ->filter(fn (array $document): bool => $document['files'] !== [])
            ->values()
            ->all();
    }
}
