<?php

declare(strict_types=1);

use App\Filament\Support\Fields;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Validator;

uses(RefreshDatabase::class);

function editorAccepts(UploadedFile $file): bool
{
    $types = Fields::editor('content')->getFileAttachmentsAcceptedFileTypes() ?? [];

    return Validator::make(
        ['file' => $file],
        ['file' => ['file', 'mimetypes:'.implode(',', $types)]],
    )->passes();
}

it('lets the editor attach an svg', function (): void {
    $svg = UploadedFile::fake()->createWithContent(
        'logo.svg',
        '<svg xmlns="http://www.w3.org/2000/svg" width="10" height="10"><rect width="10" height="10"/></svg>',
    );

    expect(editorAccepts($svg))->toBeTrue();
});

it('keeps the raster formats the editor already took', function (string $name): void {
    expect(editorAccepts(UploadedFile::fake()->image($name)))->toBeTrue();
})->with(['photo.png', 'photo.jpg', 'photo.gif', 'photo.webp']);

it('still refuses anything the web root would execute', function (): void {
    $script = UploadedFile::fake()->createWithContent('shell.php', '<?php echo 1;');

    expect(editorAccepts($script))->toBeFalse();
});

it('offers the attach modal the same list the server validates against', function (): void {
    expect(Fields::editor('content')->getFileAttachmentsAcceptedFileTypes())->toBe(Fields::IMAGE_TYPES);
});

it('hands the browser the svg type too, so the picker does not grey the file out', function (): void {
    $this->withoutVite();

    $this->actingAs(panelUser(['ViewAny:News', 'View:News', 'Create:News']))
        ->get(panel('/news/create'))
        ->assertOk()
        ->assertSee('image\/svg+xml', escape: false);
});
