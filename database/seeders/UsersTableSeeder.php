<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        // Create admin user
        User::factory()
            ->admin()
            ->create([
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'password' => bcrypt('password123')
            ]);

        // Create 20 regular customers
        User::factory()
            ->count(20)
            ->create([
                'password' => bcrypt('customer123')
            ]);
    }
} 