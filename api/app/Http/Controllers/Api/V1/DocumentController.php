<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Models\Document;
use App\Models\DocumentCategory;
use App\Models\DocumentFile;
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
            ->has('files')
            ->with(['category', 'files'])
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
                'documents' => self::documents($documents->get($category->slug)),
            ])
            ->filter(fn (array $group): bool => $group['documents'] !== [])
            ->values()
            ->all();

        if ($loose = self::documents($documents->get(''))) {
            $groups[] = ['slug' => null, 'name' => null, 'documents' => $loose];
        }

        return response()->json(['data' => $groups]);
    }

    /**
     * The file of the current locale is offered first and the rest travel
     * along, so a page can hand over a translation the visitor asks for.
     *
     * @param  iterable<Document>|null  $documents
     * @return array<int, array<string, mixed>>
     */
    private static function documents(mixed $documents): array
    {
        return collect($documents)
            ->map(function (Document $document): array {
                $current = $document->fileFor();

                return [
                    'name' => $document->name,
                    'url' => $current?->url(),
                    'size' => $current?->readableSize(),
                    'files' => $document->files
                        ->map(fn (DocumentFile $file): array => [
                            'language' => $file->language,
                            'url' => $file->url(),
                            'size' => $file->readableSize(),
                        ])
                        ->all(),
                ];
            })
            ->values()
            ->all();
    }
}
