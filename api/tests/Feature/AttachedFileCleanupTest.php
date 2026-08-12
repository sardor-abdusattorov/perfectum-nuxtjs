<?php

declare(strict_types=1);

use App\Models\TariffFile;
use App\Models\Tender;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

it('deletes the stored file together with the record', function (): void {
    Storage::fake('public');
    Storage::disk('public')->put('files/archive.pdf', 'pdf');

    TariffFile::create(['name' => 'Архив', 'file' => 'files/archive.pdf'])->delete();

    Storage::disk('public')->assertMissing('files/archive.pdf');
});

it('deletes every file of a multi file record', function (): void {
    Storage::fake('public');
    Storage::disk('public')->put('tenders/a.pdf', 'a');
    Storage::disk('public')->put('tenders/b.pdf', 'b');

    Tender::create([
        'title' => ['ru' => 'Закупка'],
        'slug' => 'zakupka',
        'content' => ['ru' => '<p>x</p>'],
        'files' => ['tenders/a.pdf', 'tenders/b.pdf'],
    ])->delete();

    Storage::disk('public')->assertMissing('tenders/a.pdf');
    Storage::disk('public')->assertMissing('tenders/b.pdf');
});
