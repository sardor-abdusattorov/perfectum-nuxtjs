<?php

use App\Models\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

pest()->extend(TestCase::class)

    ->in('Feature');

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

function panelUser(array $permissions = []): User
{
    $user = User::factory()->create();

    $user->assignRole(Role::findOrCreate('panel_user', 'web'));

    foreach ($permissions as $permission) {
        $user->givePermissionTo(Permission::findOrCreate($permission, 'web'));
    }

    app(PermissionRegistrar::class)->forgetCachedPermissions();

    return $user->refresh();
}

function panel(string $path = ''): string
{
    return '/'.Filament\Facades\Filament::getPanel('admin')->getPath().$path;
}

function coverageZip(?array $ring = null): string
{
    $ring ??= [[69.1, 41.2], [69.4, 41.2], [69.4, 41.4], [69.1, 41.2]];

    $body = pack('V', 5);
    $body .= pack('d4', 69.1, 41.2, 69.4, 41.4);
    $body .= pack('V2', 1, count($ring));
    $body .= pack('V', 0);

    foreach ($ring as [$x, $y]) {
        $body .= pack('d2', $x, $y);
    }

    $record = pack('N2', 1, strlen($body) / 2).$body;
    $header = pack('N', 9994).str_repeat("\0", 20).pack('N', (100 + strlen($record)) / 2);
    $header .= pack('V2', 1000, 5).pack('d8', 0, 0, 0, 0, 0, 0, 0, 0);

    $path = tempnam(sys_get_temp_dir(), 'cov').'.zip';
    $zip = new ZipArchive;
    $zip->open($path, ZipArchive::CREATE);
    $zip->addFromString('coverage.shp', $header.$record);
    $zip->addFromString('coverage.prj', 'GEOGCS["GCS_WGS_1984"]');
    $zip->close();

    return $path;
}
