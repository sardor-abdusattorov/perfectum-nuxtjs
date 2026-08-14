<?php

declare(strict_types=1);

use App\Filament\Resources\Documents\Pages\CreateDocument;
use App\Models\Document;
use App\Models\DocumentCategory;
use App\Models\DocumentFile;
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

it('stamps the size of every uploaded file and keeps its language', function (): void {
    Storage::fake('public');

    $category = DocumentCategory::create(['name' => ['ru' => 'Договоры']]);

    $this->actingAs($this->admin);

    Livewire::test(CreateDocument::class)
        ->fillForm([
            'category_id' => $category->id,
            'name' => ['ru' => 'Оферта', 'uz' => 'Oferta'],
            'files' => [
                [
                    'language' => 'ru',
                    // a faked create() declares a size but writes nothing, so
                    // the bytes have to be real for the disk to report them
                    'file' => [UploadedFile::fake()->createWithContent('offer-ru.pdf', str_repeat('p', 320 * 1024))],
                ],
                [
                    'language' => 'uz',
                    'file' => [UploadedFile::fake()->createWithContent('offer-uz.pdf', str_repeat('p', 64 * 1024))],
                ],
            ],
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $files = Document::firstOrFail()->files;

    expect($files)->toHaveCount(2);
    expect($files[0]->language)->toBe('ru');
    expect($files[0]->size)->toBe(320 * 1024);
    expect($files[0]->readableSize())->toBe('320 КБ');
    expect($files[1]->language)->toBe('uz');

    Storage::disk('public')->assertExists($files[0]->file);
    Storage::disk('public')->assertExists($files[1]->file);
});

it('reads a megabyte-sized file in megabytes', function (): void {
    expect((new DocumentFile(['size' => 2 * 1048576]))->readableSize())->toBe('2 МБ');
});

it('has no size before a file is attached', function (): void {
    expect((new DocumentFile)->readableSize())->toBeNull();
});

it('hands the locale the file it asked for', function (): void {
    $document = Document::create(['name' => ['ru' => 'Оферта'], 'sort' => 1]);

    $document->files()->create(['language' => 'ru', 'file' => 'a.pdf']);
    $document->files()->create(['language' => 'uz', 'file' => 'b.pdf']);

    expect($document->fileFor('uz')->file)->toBe('b.pdf');
    expect($document->fileFor('en')->file)->toBe('a.pdf');
});

it('takes the uploads with it when the document is deleted', function (): void {
    Storage::fake('public');
    Storage::disk('public')->put('uploads/documents/offer.pdf', 'pdf');

    $document = Document::create(['name' => ['ru' => 'Оферта'], 'sort' => 1]);
    $document->files()->create(['language' => 'ru', 'file' => 'uploads/documents/offer.pdf']);

    $document->delete();

    Storage::disk('public')->assertMissing('uploads/documents/offer.pdf');
    expect(DocumentFile::count())->toBe(0);
});
