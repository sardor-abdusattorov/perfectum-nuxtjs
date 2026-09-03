<?php

declare(strict_types=1);

use App\Filament\Resources\Applications\Pages\ListApplications;
use App\Filament\Resources\ApplicationStatuses\Pages\CreateApplicationStatus;
use App\Models\Application;
use App\Models\ApplicationNote;
use App\Models\ApplicationStatus;
use App\Models\ApplicationTheme;
use Filament\Actions\Testing\TestAction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->withoutVite();
});

function status(string $slug): ApplicationStatus
{
    return ApplicationStatus::query()->where('slug', $slug)->firstOrFail();
}

it('ships the statuses the team asked for', function (): void {
    expect(ApplicationStatus::query()->orderBy('sort')->pluck('slug')->all())
        ->toBe(['new', 'processed', 'accepted', 'no-answer', 'wrong-number', 'cdma-question']);

    expect(status('new')->is_default)->toBeTrue()
        ->and(status('no-answer')->name)->toBe('Не отвечает');
});

it('lets the team add a status of their own', function (): void {
    $this->actingAs(panelUser(['ViewAny:ApplicationStatus', 'Create:ApplicationStatus']));

    Livewire::test(CreateApplicationStatus::class)
        ->fillForm([
            'name.ru' => 'Перезвонить позже',
            'name.uz' => 'Keyinroq qoʻngʻiroq qilish',
            'color' => 'warning',
            'sort' => 10,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $added = ApplicationStatus::query()->where('color', 'warning')->where('sort', 10)->firstOrFail();

    expect($added->name)->toBe('Перезвонить позже')
        ->and($added->slug)->toBe('perezvonit-pozzhe')
        ->and(ApplicationStatus::options())->toHaveKey($added->getKey());
});

it('moves the default flag rather than keeping two', function (): void {
    $accepted = status('accepted');

    $accepted->update(['is_default' => true]);

    expect($accepted->fresh()->is_default)->toBeTrue()
        ->and(status('new')->is_default)->toBeFalse()
        ->and(ApplicationStatus::default()->slug)->toBe('accepted');
});

it('refuses to delete a status something is filed under', function (): void {
    Application::factory()->create(['status_id' => status('new')->getKey()]);

    expect(status('new')->isInUse())->toBeTrue()
        ->and(status('accepted')->isInUse())->toBeFalse();
});

it('starts the clock when an operator moves the application off the arrival status', function (): void {
    Carbon::setTestNow('2026-09-03 09:00:00');
    $application = Application::factory()->create();

    expect($application->processed_at)->toBeNull();

    Carbon::setTestNow('2026-09-03 09:25:00');
    $application->update(['status_id' => status('no-answer')->getKey()]);

    expect($application->fresh()->processed_at?->toDateTimeString())->toBe('2026-09-03 09:25:00')
        ->and($application->fresh()->handlingSeconds())->toBe(1500)
        ->and(Application::readableHandlingTime(1500))->toBe('25 мин');

    Carbon::setTestNow('2026-09-03 10:00:00');
    $application->update(['status_id' => status('processed')->getKey()]);

    expect($application->fresh()->processed_at?->toDateTimeString())->toBe('2026-09-03 09:25:00');

    Carbon::setTestNow();
});

it('keeps the first handling time when an application is put back to the arrival status', function (): void {
    Carbon::setTestNow('2026-09-03 09:00:00');
    $application = Application::factory()->create();

    Carbon::setTestNow('2026-09-03 09:10:00');
    $application->update(['status_id' => status('no-answer')->getKey()]);

    Carbon::setTestNow('2026-09-03 11:00:00');
    $application->update(['status_id' => status('new')->getKey()]);

    expect($application->fresh()->processed_at?->toDateTimeString())->toBe('2026-09-03 09:10:00');

    Carbon::setTestNow();
});

/**
 * A bulk update goes through the query builder, which fires no model event, so
 * the handling time has to be written by the action itself.
 */
it('stamps the handling time on a bulk status change too', function (): void {
    Carbon::setTestNow('2026-09-03 09:00:00');
    $applications = Application::factory()->count(3)->create();

    $admin = panelUser(['ViewAny:Application', 'View:Application']);
    $admin->givePermissionTo(Permission::findOrCreate('Update:Application', 'web'));
    $this->actingAs($admin->refresh());

    Carbon::setTestNow('2026-09-03 09:30:00');

    Livewire::test(ListApplications::class)
        ->selectTableRecords($applications->pluck('id')->all())
        ->callAction(TestAction::make('changeStatus')->table()->bulk(), [
            'status_id' => status('accepted')->getKey(),
        ]);

    foreach ($applications as $application) {
        expect($application->fresh()->status->slug)->toBe('accepted')
            ->and($application->fresh()->handlingSeconds())->toBe(1800);
    }

    Carbon::setTestNow();
});

it('averages the handling time over what the list is showing', function (): void {
    Carbon::setTestNow('2026-09-03 09:00:00');

    foreach ([600, 1200, 1800] as $seconds) {
        $application = Application::factory()->create();
        $application->forceFill(['processed_at' => now()->addSeconds($seconds)])->saveQuietly();
    }

    Application::factory()->create();

    expect(Application::averageHandlingSeconds(Application::query()))->toBe(1200)
        ->and(Application::readableHandlingTime(1200))->toBe('20 мин');

    Carbon::setTestNow();
});

it('reads a long wait in hours and days', function (int $seconds, string $expected): void {
    expect(Application::readableHandlingTime($seconds))->toBe($expected);
})->with([
    [45, '45 с'],
    [3600 * 3 + 60 * 12, '3 ч 12 мин'],
    [86400 * 2 + 3600 * 5, '2 д 5 ч'],
]);

/**
 * «Не отвечает, перезвонить после обеда» — то, что оператор пишет себе и
 * коллегам; посетитель этого не видит.
 */
it('keeps every note instead of replacing the one before it', function (): void {
    $application = Application::factory()->create();

    $admin = panelUser(['ViewAny:Application', 'View:Application']);
    $admin->givePermissionTo(Permission::findOrCreate('Update:Application', 'web'));
    $this->actingAs($admin->refresh());

    Livewire::test(ListApplications::class)
        ->callAction(TestAction::make('changeStatus')->table($application), [
            'status_id' => status('no-answer')->getKey(),
            'note' => 'Не берёт трубку',
        ]);

    Livewire::test(ListApplications::class)
        ->callAction(TestAction::make('changeStatus')->table($application), [
            'status_id' => status('accepted')->getKey(),
            'note' => 'Перезвонили, ждёт мастера',
        ]);

    $notes = $application->fresh()->notes;

    expect($notes)->toHaveCount(2)
        ->and($notes->first()->body)->toBe('Перезвонили, ждёт мастера')
        ->and($notes->last()->body)->toBe('Не берёт трубку')
        ->and($notes->first()->authorLabel())->toBe($admin->name)
        ->and($application->fresh()->status->slug)->toBe('accepted');
});

it('does not add an empty note when the status changes without one', function (): void {
    $application = Application::factory()->create();

    $admin = panelUser(['ViewAny:Application', 'View:Application']);
    $admin->givePermissionTo(Permission::findOrCreate('Update:Application', 'web'));
    $this->actingAs($admin->refresh());

    Livewire::test(ListApplications::class)
        ->callAction(TestAction::make('changeStatus')->table($application), [
            'status_id' => status('processed')->getKey(),
            'note' => '   ',
        ]);

    expect($application->fresh()->notes)->toHaveCount(0);
});

it('does not touch the notes when several applications change status at once', function (): void {
    $applications = Application::factory()->count(2)->create();
    $applications->each(fn (Application $item) => $item->addNote('Своя заметка '.$item->getKey()));

    $admin = panelUser(['ViewAny:Application', 'View:Application']);
    $admin->givePermissionTo(Permission::findOrCreate('Update:Application', 'web'));
    $this->actingAs($admin->refresh());

    Livewire::test(ListApplications::class)
        ->selectTableRecords($applications->pluck('id')->all())
        ->callAction(TestAction::make('changeStatus')->table()->bulk(), [
            'status_id' => status('accepted')->getKey(),
        ]);

    foreach ($applications as $application) {
        expect($application->fresh()->notes)->toHaveCount(1)
            ->and($application->fresh()->notes->first()->body)->toBe('Своя заметка '.$application->getKey())
            ->and($application->fresh()->status->slug)->toBe('accepted');
    }
});

it('keeps the journal out of what the site can write', function (): void {
    $this->postJson(route('api.v1.applications.store'), [
        'phone' => '998901234567',
        'theme' => ApplicationTheme::create(['name' => ['ru' => 'Тема']])->getKey(),
        'message' => 'Текст',
        'note' => 'подсунутая заметка',
    ])->assertCreated();

    expect(ApplicationNote::query()->count())->toBe(0);
});
