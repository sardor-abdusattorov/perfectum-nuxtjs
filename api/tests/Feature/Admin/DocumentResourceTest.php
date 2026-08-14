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

it('stamps the size of the uploaded file', function (): void {
    Storage::fake('public');

    $category = DocumentCategory::create([
        'name' => ['ru' => 'Договоры'],
        'slug' => 'dogovory',
    ]);

    $this->actingAs($this->admin);

    Livewire::test(CreateDocument::class)
        ->fillForm([
            'category_id' => $category->id,
            'name' => ['ru' => 'Оферта', 'uz' => 'Oferta'],
            // a faked create() declares a size but writes nothing, so the
            // bytes have to be real for the disk to report them
            'file' => UploadedFile::fake()->createWithContent('offer.pdf', str_repeat('p', 320 * 1024)),
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $document = Document::firstOrFail();

    expect($document->size)->toBe(320 * 1024);
    expect($document->readableSize())->toBe('320 КБ');
    Storage::disk('public')->assertExists($document->file);
});

it('reads a megabyte-sized file in megabytes', function (): void {
    $document = new Document(['size' => 2 * 1048576]);

    expect($document->readableSize())->toBe('2 МБ');
});

it('has no size before a file is attached', function (): void {
    expect((new Document)->readableSize())->toBeNull();
});
