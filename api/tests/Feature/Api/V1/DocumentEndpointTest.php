<?php

declare(strict_types=1);

use App\Models\Document;
use App\Models\DocumentCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function documentCategory(string $slug, int $sort = 1): DocumentCategory
{
    return DocumentCategory::create([
        'name' => ['ru' => ucfirst($slug), 'uz' => ucfirst($slug)],
        'slug' => $slug,
        'sort' => $sort,
    ]);
}

function document(array $attributes = []): Document
{
    return Document::create(array_merge([
        'name' => ['ru' => 'Оферта', 'uz' => 'Oferta'],
        'file' => 'uploads/documents/offer.pdf',
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
        ->assertJsonPath('data.0.slug', 'dogovory')
        ->assertJsonPath('data.0.documents.0.name', 'Оферта')
        ->assertJsonPath('data.1.slug', 'politiki');
});

it('skips a category with nothing published in it', function (): void {
    documentCategory('pusto');
    $used = documentCategory('dogovory', 2);

    document(['category_id' => $used->id]);

    $this->getJson(route('api.v1.documents'))
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.slug', 'dogovory');
});

it('leaves out an unpublished document and one without a file', function (): void {
    $category = documentCategory('dogovory');

    document(['category_id' => $category->id, 'name' => ['ru' => 'Виден']]);
    document(['category_id' => $category->id, 'name' => ['ru' => 'Скрыт'], 'status' => false]);
    document(['category_id' => $category->id, 'name' => ['ru' => 'Без файла'], 'file' => '']);

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
        ->assertJsonPath('data.1.slug', null)
        ->assertJsonPath('data.1.documents.0.name', 'Сам по себе');
});

it('answers in the requested locale', function (): void {
    $category = documentCategory('dogovory');

    document(['category_id' => $category->id, 'name' => ['ru' => 'Оферта', 'uz' => 'Oferta']]);

    $this->getJson(route('api.v1.documents'), ['X-Locale' => 'uz'])
        ->assertOk()
        ->assertJsonPath('data.0.documents.0.name', 'Oferta');
});
