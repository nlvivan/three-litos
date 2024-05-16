<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Role::firstOrCreate(['name' => 'Admin']);
        Role::firstOrCreate(['name' => 'Staff']);
        Role::firstOrCreate(['name' => 'customer']);

        $user = User::firstOrCreate([
            'name' => 'Administrator',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('password'),
        ]);

        $user->assignRole('Admin');
    }
}
