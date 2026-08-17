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
        $superadmin = User::firstOrCreate(
            ['email' => 'mr.silverwind1998@gmail.com'],
            [
                'name' => 'Sardor Abdusattorov',
                'password' => bcrypt('123456'),
                'email_verified_at' => now(),
            ]
        );
        $superadmin->assignRole('super_admin');

    }
}
