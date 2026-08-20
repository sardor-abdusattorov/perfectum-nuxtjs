<?php

declare(strict_types=1);

use App\Models\Document;
use App\Models\DocumentCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    Storage::fake('public');
});

function documentCategory(string $name, int $sort = 1): DocumentCategory
{
    return DocumentCategory::create([
        'name' => ['ru' => ucfirst($name), 'uz' => ucfirst($name)],
        'sort' => $sort,
    ]);
}

function document(array $attributes = [], array $files = ['ru' => 'uploads/documents/offer-ru.pdf']): Document
{
    foreach ($files as $path) {
        Storage::disk('public')->put($path, 'pdf');
    }

    return Document::create(array_merge([
        'name' => ['ru' => 'Оферта', 'uz' => 'Oferta'],
        'file' => $files,
        'sort' => 1,
    ], $attributes));
}

it('groups the documents under their category', function (): void {
    $contracts = documentCategory('dogovory', 1);
    $policies = documentCategory('politiki', 2);

    document(['category_id' => $policies->id, 'name' => ['ru' => 'Политика']]);
    document(['category_id' => $contracts->id, 'name' => ['ru' => 'Оферта']]);

    $this->getJson(route('api.v1.documents'))
        ->assertOk()
        ->assertJsonCount(2, 'data')
        ->assertJsonPath('data.0.name', 'Dogovory')
        ->assertJsonPath('data.0.documents.0.name', 'Оферта')
        ->assertJsonPath('data.1.name', 'Politiki');
});

it('skips a category with nothing published in it', function (): void {
    documentCategory('pusto');
    $used = documentCategory('dogovory', 2);

    document(['category_id' => $used->id]);

    $this->getJson(route('api.v1.documents'))
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.name', 'Dogovory');
});

it('leaves out an unpublished document and one without a file', function (): void {
    $category = documentCategory('dogovory');

    document(['category_id' => $category->id, 'name' => ['ru' => 'Виден']]);
    document(['category_id' => $category->id, 'name' => ['ru' => 'Скрыт'], 'status' => false]);
    document(['category_id' => $category->id, 'name' => ['ru' => 'Без файла']], []);

    $this->getJson(route('api.v1.documents'))
        ->assertOk()
        ->assertJsonCount(1, 'data.0.documents')
        ->assertJsonPath('data.0.documents.0.name', 'Виден');
});

it('puts an uncategorised document in a trailing group', function (): void {
    $category = documentCategory('dogovory');

    document(['category_id' => $category->id, 'name' => ['ru' => 'В категории']]);
    document(['category_id' => null, 'name' => ['ru' => 'Сам по себе']]);

    $this->getJson(route('api.v1.documents'))
        ->assertOk()
        ->assertJsonCount(2, 'data')
        ->assertJsonPath('data.1.id', null)
        ->assertJsonPath('data.1.documents.0.name', 'Сам по себе');
});

it('answers in the requested locale', function (): void {
    $category = documentCategory('dogovory');

    document(['category_id' => $category->id, 'name' => ['ru' => 'Оферта', 'uz' => 'Oferta']]);

    $this->getJson(route('api.v1.documents'), ['X-Locale' => 'uz'])
        ->assertOk()
        ->assertJsonPath('data.0.documents.0.name', 'Oferta');
});

it('hands over the file of the asked language', function (): void {
    $category = documentCategory('dogovory');

    document(['category_id' => $category->id], [
        'ru' => 'uploads/documents/offer-ru.pdf',
        'uz' => 'uploads/documents/offer-uz.pdf',
    ]);

    $this->getJson(route('api.v1.documents', ['lang' => 'uz']))
        ->assertOk()
        ->assertJsonPath('data.0.documents.0.url', fn (string $url): bool => str_ends_with($url, 'offer-uz.pdf'))
        ->assertJsonCount(2, 'data.0.documents.0.files')
        ->assertJsonPath('data.0.documents.0.files.1.language', 'uz');
});

it('falls back to the default language when the locale has no file of its own', function (): void {
    $category = documentCategory('dogovory');

    document(['category_id' => $category->id], ['ru' => 'uploads/documents/offer-ru.pdf']);

    $this->getJson(route('api.v1.documents', ['lang' => 'uz']))
        ->assertOk()
        ->assertJsonPath('data.0.documents.0.url', fn (string $url): bool => str_ends_with($url, 'offer-ru.pdf'));
});

it('drops a document whose file went missing from the disk', function (): void {
    $category = documentCategory('dogovory');

    document(['category_id' => $category->id, 'name' => ['ru' => 'Виден']]);
    document(['category_id' => $category->id, 'name' => ['ru' => 'Потерян']], ['ru' => 'uploads/documents/lost.pdf']);

    Storage::disk('public')->delete('uploads/documents/lost.pdf');

    $this->getJson(route('api.v1.documents'))
        ->assertOk()
        ->assertJsonCount(1, 'data.0.documents')
        ->assertJsonPath('data.0.documents.0.name', 'Виден');
});

it('carries the record id so a client can point at one document', function (): void {
    $category = documentCategory('dogovory');
    $offer = document(['category_id' => $category->id]);

    $this->getJson(route('api.v1.documents'))
        ->assertOk()
        ->assertJsonPath('data.0.documents.0.id', $offer->id);
});
