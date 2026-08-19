<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
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
        $superadmin->assignRole(Role::findOrCreate('super_admin', 'web'));
    }
}
