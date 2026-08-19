<?php

declare(strict_types=1);

use App\Models\Device;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    Storage::fake('public');
    File::ensureDirectoryExists(storage_path('app/testing-old-files/images'));
    File::put(storage_path('app/testing-old-files/images/photo.jpg'), 'старые байты');
});

afterEach(function (): void {
    File::deleteDirectory(storage_path('app/testing-old-files'));
});

it('carries the old uploads onto the public disk', function (): void {
    $this->artisan('old-files:import', ['--source' => 'testing-old-files'])
        ->assertSuccessful();

    Storage::disk('public')->assertExists('images/photo.jpg');
    expect(Storage::disk('public')->get('images/photo.jpg'))->toBe('старые байты');
});

it('leaves a file alone when it is already in place', function (): void {
    Storage::disk('public')->put('images/photo.jpg', 'старые байты');

    $this->artisan('old-files:import', ['--source' => 'testing-old-files'])
        ->expectsOutputToContain('уже на месте: 1')
        ->assertSuccessful();
});

it('names the referenced files the folder does not hold', function (): void {
    Device::query()->create([
        'name' => ['ru' => 'Тест'],
        'slug' => 'test',
        'image' => 'images/lost.jpg',
    ]);

    $this->artisan('old-files:import', ['--source' => 'testing-old-files'])
        ->expectsOutputToContain('images/lost.jpg')
        ->assertSuccessful();
});

it('explains itself when the folder is missing', function (): void {
    $this->artisan('old-files:import', ['--source' => 'nowhere-at-all'])
        ->assertFailed();
});
