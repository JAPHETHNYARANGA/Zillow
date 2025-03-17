<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin user
        if (!User::where('email', 'admin@example.com')->exists()) {
            $admin = User::create([
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'password' => bcrypt('admin123'),
                'status' => 'active',
            ]);

            $adminRole = Role::where('name', 'admin')->firstOrFail();
            $admin->roles()->attach($adminRole);
        }

        // Broker user
        if (!User::where('email', 'broker@example.com')->exists()) {
            $broker = User::create([
                'name' => 'Test Broker',
                'email' => 'broker@example.com',
                'password' => bcrypt('password123'),
                'status' => 'active',
            ]);

            $brokerRole = Role::where('name', 'broker')->firstOrFail();
            $broker->roles()->attach($brokerRole);
        }
    }
}