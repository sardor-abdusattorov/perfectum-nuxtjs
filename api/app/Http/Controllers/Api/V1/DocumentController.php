<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Models\Document;
use App\Models\DocumentCategory;
use Illuminate\Http\JsonResponse;

class DocumentController
{
    /**
     * The page lists documents under their category heading, so the grouping
     * is done here and an uncategorised file lands in its own trailing group.
     */
    public function __invoke(): JsonResponse
    {
        $documents = Document::query()
            ->published()
            ->whereNot('file', '')
            ->with('category')
            ->ordered()
            ->get()
            ->groupBy(fn (Document $document): string => (string) $document->category?->slug);

        $groups = DocumentCategory::query()
            ->published()
            ->ordered()
            ->get()
            ->map(fn (DocumentCategory $category): array => [
                'slug' => $category->slug,
                'name' => $category->name,
                'documents' => self::files($documents->get($category->slug)),
            ])
            ->filter(fn (array $group): bool => $group['documents'] !== [])
            ->values()
            ->all();

        if ($loose = self::files($documents->get(''))) {
            $groups[] = ['slug' => null, 'name' => null, 'documents' => $loose];
        }

        return response()->json(['data' => $groups]);
    }

    /**
     * @param  iterable<Document>|null  $documents
     * @return array<int, array<string, mixed>>
     */
    private static function files(mixed $documents): array
    {
        return collect($documents)
            ->map(fn (Document $document): array => [
                'name' => $document->name,
                'url' => $document->url(),
                'size' => $document->readableSize(),
            ])
            ->values()
            ->all();
    }
}
