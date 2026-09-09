<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Models\Document;
use App\Models\DocumentCategory;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DocumentController
{
    public function index(): JsonResponse
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
     * One document by the `id` the list carries, for a client that was pointed
     * straight at it and has no list to look in.
     */
    public function show(Document $document): JsonResponse
    {
        $present = self::present($document->loadMissing('category'));

        if ($present['files'] === []) {
            throw new NotFoundHttpException;
        }

        return response()->json(['data' => [
            ...$present,
            'category' => $document->category === null ? null : [
                'id' => $document->category->id,
                'name' => $document->category->name,
            ],
        ]]);
    }

    /**
     * @param  iterable<Document>|null  $documents
     * @return array<int, array<string, mixed>>
     */
    private static function documents(mixed $documents): array
    {
        return collect($documents)
            ->map(fn (Document $document): array => self::present($document))
            ->filter(fn (array $document): bool => $document['files'] !== [])
            ->values()
            ->all();
    }

    /**
     * @return array<string, mixed>
     */
    private static function present(Document $document): array
    {
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
    }
}
