<?php

declare(strict_types=1);

use App\Models\Document;
use App\Models\DocumentCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

function documentWith(array $attributes = []): Document
{
    Storage::disk('public')->put('uploads/documents/oferta.pdf', 'pdf');

    $category = DocumentCategory::create([
        'name' => ['ru' => 'Оферта', 'uz' => 'Oferta'],
    ]);

    return Document::create(array_merge([
        'category_id' => $category->getKey(),
        'name' => ['ru' => 'Публичная оферта', 'uz' => 'Ommaviy oferta'],
        'file' => ['ru' => 'uploads/documents/oferta.pdf'],
        'status' => true,
    ], $attributes));
}

beforeEach(function (): void {
    Storage::fake('public');
});

it('lets a document be downloaded unless someone says otherwise', function (): void {
    $document = documentWith();

    expect($document->is_downloadable)->toBeTrue();

    $this->getJson(route('api.v1.documents'))
        ->assertOk()
        ->assertJsonPath('data.0.documents.0.downloadable', true);
});

it('tells the site when a document is for reading only', function (): void {
    documentWith(['is_downloadable' => false]);

    $this->getJson(route('api.v1.documents'))
        ->assertOk()
        ->assertJsonPath('data.0.documents.0.downloadable', false);
});

it('still hands over the address, so the document opens in a new tab either way', function (): void {
    documentWith(['is_downloadable' => false]);

    $url = $this->getJson(route('api.v1.documents'))
        ->assertOk()
        ->json('data.0.documents.0.url');

    expect($url)->toContain('uploads/documents/oferta.pdf');
});
