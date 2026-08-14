<?php

declare(strict_types=1);

use App\Filament\Resources\Documents\Pages\CreateDocument;
use App\Models\Document;
use App\Models\DocumentCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->withoutVite();

    $this->admin = User::factory()->create();

    foreach (['ViewAny', 'View', 'Create', 'Update'] as $verb) {
        $this->admin->givePermissionTo(Permission::findOrCreate("{$verb}:Document", 'web'));
    }

    $this->admin->refresh();
});

it('stamps the size of the file on the tab it was uploaded to', function (): void {
    Storage::fake('public');

    $category = DocumentCategory::create(['name' => ['ru' => 'Договоры']]);

    $this->actingAs($this->admin);

    Livewire::test(CreateDocument::class)
        ->fillForm([
            'category_id' => $category->id,
            'name' => ['ru' => 'Оферта', 'uz' => 'Oferta'],
            // a faked create() declares a size but writes nothing, so the
            // bytes have to be real for the disk to report them
            'file' => [
                'ru' => [UploadedFile::fake()->createWithContent('offer-ru.pdf', str_repeat('p', 320 * 1024))],
                'uz' => [UploadedFile::fake()->createWithContent('offer-uz.pdf', str_repeat('p', 64 * 1024))],
            ],
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $document = Document::firstOrFail();

    expect($document->getTranslations('file'))->toHaveKeys(['ru', 'uz']);
    expect($document->readableSize('ru'))->toBe('320 КБ');
    expect($document->readableSize('uz'))->toBe('64 КБ');

    Storage::disk('public')->assertExists($document->getTranslation('file', 'ru'));
    Storage::disk('public')->assertExists($document->getTranslation('file', 'uz'));
});

it('reads a megabyte-sized file in megabytes', function (): void {
    $document = new Document(['size' => ['ru' => 2 * 1048576]]);

    expect($document->readableSize('ru'))->toBe('2 МБ');
});

it('has no size before a file is attached', function (): void {
    expect((new Document)->readableSize())->toBeNull();
});

it('hands the locale the file it asked for', function (): void {
    $document = Document::create([
        'name' => ['ru' => 'Оферта'],
        'file' => ['ru' => 'a.pdf', 'uz' => 'b.pdf'],
        'sort' => 1,
    ]);

    expect($document->url('uz'))->toContain('b.pdf');
    expect($document->url('ru'))->toContain('a.pdf');
});

it('takes the uploads with it when the document is deleted', function (): void {
    Storage::fake('public');
    Storage::disk('public')->put('uploads/documents/offer-ru.pdf', 'pdf');
    Storage::disk('public')->put('uploads/documents/offer-uz.pdf', 'pdf');

    Document::create([
        'name' => ['ru' => 'Оферта'],
        'file' => ['ru' => 'uploads/documents/offer-ru.pdf', 'uz' => 'uploads/documents/offer-uz.pdf'],
        'sort' => 1,
    ])->delete();

    Storage::disk('public')->assertMissing('uploads/documents/offer-ru.pdf');
    Storage::disk('public')->assertMissing('uploads/documents/offer-uz.pdf');
});
