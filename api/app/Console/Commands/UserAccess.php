<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\User;
use BezhanSalleh\FilamentShield\Support\Utils;
use Filament\Facades\Filament;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

/**
 * Answers «why does this account see nothing» without guessing: what roles it
 * carries, how many permissions those roles actually grant, whether the panel
 * lets it in and which resources its sidebar will hold.
 */
final class UserAccess extends Command
{
    protected $signature = 'user:access {email} {--fresh : forget the cached permissions first} {--grant : give the account the panel_user role}';

    protected $description = 'Show what a panel account may see';

    public function handle(): int
    {
        if ($this->option('fresh')) {
            app(PermissionRegistrar::class)->forgetCachedPermissions();
            $this->line('Кэш прав сброшен.');
        }

        $user = User::query()->where('email', $this->argument('email'))->first();

        if ($user === null) {
            $this->error('Пользователь не найден: '.$this->argument('email'));

            return self::FAILURE;
        }

        $panel = Filament::getPanel('admin');
        Filament::setCurrentPanel($panel);

        if ($this->option('grant')) {
            $user->assignRole(Role::findOrCreate(Utils::getPanelUserRoleName(), Utils::getFilamentAuthGuard()));
            app(PermissionRegistrar::class)->forgetCachedPermissions();
            $user = $user->refresh();
            $this->line('Роль '.Utils::getPanelUserRoleName().' выдана.');
        }

        auth()->login($user);

        $this->line('Пользователь: '.$user->name.' <'.$user->email.'>');
        $this->line('Роли: '.($user->getRoleNames()->implode(', ') ?: '— нет —'));
        $this->line('Прав через роли: '.$user->getAllPermissions()->count());
        $this->line('Пускает в панель: '.($user->canAccessPanel($panel) ? 'да' : 'НЕТ'));

        if (! $user->canAccessPanel($panel)) {
            $this->warn('Нужна роль panel_user или super_admin — без неё панель не откроется совсем.');
            $this->warn('Выдать её этому аккаунту: php artisan user:access '.$user->email.' --grant');
        }

        $visible = collect($panel->getResources())
            ->filter(fn (string $resource): bool => $resource::canViewAny())
            ->map(fn (string $resource): string => $resource::getPluralModelLabel())
            ->sort()
            ->values();

        $this->newLine();
        $this->line('Разделы в меню ('.$visible->count().'):');

        $visible->isEmpty()
            ? $this->warn('  пусто — ни одного права вида ViewAny:*')
            : $visible->each(fn (string $label) => $this->line('  '.$label));

        $granted = $user->getAllPermissions()->pluck('name')->filter(
            fn (string $name): bool => str_starts_with($name, 'ViewAny:')
        )->sort()->values();

        $this->newLine();
        $this->line('Права ViewAny ('.$granted->count().'): '.($granted->implode(', ') ?: '— нет —'));

        return self::SUCCESS;
    }
}
