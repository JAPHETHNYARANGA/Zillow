<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create an admin user
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('admin123'),
            'status' => 'active',
        ]);

        // Assign admin role
        $adminRole = Role::where('name', 'admin')->firstOrFail();
        $admin->roles()->attach($adminRole);

        // Optional: Create a sample broker user
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