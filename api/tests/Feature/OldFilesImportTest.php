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

it('lays a referenced file down at its new address', function (): void {
    Device::query()->create([
        'name' => ['ru' => 'Тест'],
        'slug' => 'test',
        'image' => 'uploads/devices/legacy/photo.jpg',
    ]);

    $this->artisan('old-files:import', ['--source' => 'testing-old-files'])
        ->assertSuccessful();

    Storage::disk('public')->assertExists('uploads/devices/legacy/photo.jpg');
    expect(Storage::disk('public')->get('uploads/devices/legacy/photo.jpg'))->toBe('старые байты');
});

it('leaves a file alone when it is already in place', function (): void {
    Device::query()->create([
        'name' => ['ru' => 'Тест'],
        'slug' => 'test',
        'image' => 'uploads/devices/legacy/photo.jpg',
    ]);
    Storage::disk('public')->put('uploads/devices/legacy/photo.jpg', 'уже лежит');

    $this->artisan('old-files:import', ['--source' => 'testing-old-files'])
        ->expectsOutputToContain('уже на месте: 1')
        ->assertSuccessful();

    expect(Storage::disk('public')->get('uploads/devices/legacy/photo.jpg'))->toBe('уже лежит');
});

it('carries nothing the content does not reference', function (): void {
    $this->artisan('old-files:import', ['--source' => 'testing-old-files'])
        ->expectsOutputToContain('не понадобилось: 1')
        ->assertSuccessful();

    expect(Storage::disk('public')->allFiles())->toBe([]);
});

it('names the referenced files the folder does not hold', function (): void {
    Device::query()->create([
        'name' => ['ru' => 'Тест'],
        'slug' => 'test',
        'image' => 'uploads/devices/legacy/lost.jpg',
    ]);

    $this->artisan('old-files:import', ['--source' => 'testing-old-files'])
        ->expectsOutputToContain('uploads/devices/legacy/lost.jpg')
        ->assertSuccessful();
});

it('names the missing files without copying anything', function (): void {
    Device::query()->create([
        'name' => ['ru' => 'Тест'],
        'slug' => 'test',
        'image' => 'uploads/devices/legacy/photo.jpg',
    ]);

    $this->artisan('old-files:import', ['--check' => true])
        ->expectsOutputToContain('uploads/devices/legacy/photo.jpg')
        ->assertSuccessful();

    expect(Storage::disk('public')->allFiles())->toBe([]);
});

it('reports a clean disk when every referenced file is in place', function (): void {
    Device::query()->create([
        'name' => ['ru' => 'Тест'],
        'slug' => 'test',
        'image' => 'uploads/devices/legacy/photo.jpg',
    ]);
    Storage::disk('public')->put('uploads/devices/legacy/photo.jpg', 'на месте');

    $this->artisan('old-files:import', ['--check' => true])
        ->expectsOutputToContain('Все файлы, на которые ссылается контент, на месте.')
        ->assertSuccessful();
});

it('explains itself when the folder is missing', function (): void {
    $this->artisan('old-files:import', ['--source' => 'nowhere-at-all'])
        ->assertFailed();
});
