<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * The panel is reachable from the internet, so the seeder never invents a
     * password anyone could guess: it takes ADMIN_PASSWORD, and without one it
     * draws a random password and prints it once for the operator to store.
     */
    public function run(): void
    {
        $role = Role::findOrCreate('super_admin', 'web');

        $email = (string) env('ADMIN_EMAIL', 'admin@perfectum.uz');
        $password = (string) env('ADMIN_PASSWORD', '');

        if ($password === '') {
            $password = Str::password(16);
            $this->command?->warn("Admin password for {$email}: {$password}");
        }

        $user = User::firstOrNew(['email' => $email]);

        if (! $user->exists) {
            $user->fill([
                'name' => (string) env('ADMIN_NAME', 'Administrator'),
                'password' => Hash::make($password),
                'email_verified_at' => now(),
            ])->save();
        }

        $user->assignRole($role);
    }
}
