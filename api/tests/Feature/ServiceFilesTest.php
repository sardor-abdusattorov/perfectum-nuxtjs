<?php

declare(strict_types=1);

use App\Filament\Support\Fields;
use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

function serviceWithFiles(array $files): Service
{
    $category = ServiceCategory::create([
        'name' => ['ru' => 'Связь', 'uz' => 'Aloqa'],
        'slug' => 'svyaz',
    ]);

    return Service::create([
        'category_id' => $category->getKey(),
        'name' => ['ru' => 'SMS-информирование', 'uz' => 'SMS'],
        'slug' => 'sms',
        'content' => ['ru' => '<p>Текст</p>', 'uz' => '<p>Matn</p>'],
        'files' => $files,
        'status' => true,
    ]);
}

it('hands the site every document attached to a service', function (): void {
    Storage::fake('public');
    Storage::disk('public')->put('uploads/services/price.pdf', 'pdf');
    Storage::disk('public')->put('uploads/services/rules.pdf', 'pdf');

    serviceWithFiles(['uploads/services/price.pdf', 'uploads/services/rules.pdf']);

    $files = $this->getJson(route('api.v1.services.show', ['service' => 'sms']))
        ->assertOk()
        ->json('data.files');

    expect($files)->toHaveCount(2)
        ->and($files[0])->toContain('uploads/services/price.pdf');
});

it('leaves out a document whose file is gone', function (): void {
    Storage::fake('public');
    Storage::disk('public')->put('uploads/services/price.pdf', 'pdf');

    serviceWithFiles(['uploads/services/price.pdf', 'uploads/services/missing.pdf']);

    $this->getJson(route('api.v1.services.show', ['service' => 'sms']))
        ->assertOk()
        ->assertJsonCount(1, 'data.files');
});

it('says the service has no documents rather than dropping the key', function (): void {
    serviceWithFiles([]);

    $this->getJson(route('api.v1.services.show', ['service' => 'sms']))
        ->assertOk()
        ->assertJsonPath('data.files', []);
});

it('takes the documents with the service when it is deleted', function (): void {
    Storage::fake('public');
    Storage::disk('public')->put('uploads/services/price.pdf', 'pdf');

    $service = serviceWithFiles(['uploads/services/price.pdf']);

    $service->delete();

    Storage::disk('public')->assertMissing('uploads/services/price.pdf');
});

it('accepts the same kinds of file a tender does', function (): void {
    $upload = UploadedFile::fake()->create('price.pdf', 12, 'application/pdf');

    expect(Fields::DOCUMENT_TYPES)->toContain($upload->getMimeType());
});
