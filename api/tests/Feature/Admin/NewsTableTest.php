<?php

declare(strict_types=1);

use App\Enums\Network;
use App\Filament\Resources\News\Pages\ListNews;
use App\Models\News;
use App\Models\NewsCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->withoutVite();

    $user = panelUser();

    foreach (['ViewAny', 'View', 'Create', 'Update'] as $ability) {
        $user->givePermissionTo(Permission::findOrCreate("{$ability}:News", 'web'));
    }

    $this->actingAs($user->refresh());

    $category = NewsCategory::create([
        'name' => ['ru' => 'Компания'], 'slug' => 'kompaniya', 'network' => Network::Both,
    ]);

    $this->fiveG = News::create([
        'title' => ['ru' => 'Технические работы'],
        'slug' => 'texniceskie-raboty',
        'content' => ['ru' => '<p>Текст</p>'],
        'network' => Network::FiveG,
        'category_id' => $category->getKey(),
        'published_at' => now()->subDay(),
    ]);

    $this->cdma = News::create([
        'title' => ['ru' => 'SMS-информирование'],
        'slug' => 'sms-informirovanie',
        'content' => ['ru' => '<p>Текст</p>'],
        'network' => Network::Cdma,
        'category_id' => $category->getKey(),
        'published_at' => now()->subDay(),
    ]);
});

/**
 * Most of the feed belongs to one section or the other, so an editor looking
 * for a CDMA notice should not have to read past the 5G ones.
 */
it('narrows the news list to one network', function (): void {
    Livewire::test(ListNews::class)
        ->assertCanSeeTableRecords([$this->fiveG, $this->cdma])
        ->filterTable('network', Network::Cdma->value)
        ->assertCanSeeTableRecords([$this->cdma])
        ->assertCanNotSeeTableRecords([$this->fiveG])
        ->filterTable('network', Network::FiveG->value)
        ->assertCanSeeTableRecords([$this->fiveG])
        ->assertCanNotSeeTableRecords([$this->cdma]);
});
