<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'John Buyer',
                'email' => 'john.buyer@example.com',
                'password' => bcrypt('password123'),
                'role' => 'buyer',
                'phone' => '123-456-7890',
                'bio' => 'Looking for a cozy home in the city.',
            ],
            [
                'name' => 'Sarah Seller',
                'email' => 'sarah.seller@example.com',
                'password' => bcrypt('password123'),
                'role' => 'seller',
                'phone' => '234-567-8901',
                'bio' => 'Selling my properties to move abroad.',
            ],
            [
                'name' => 'Mike Agent',
                'email' => 'mike.agent@example.com',
                'password' => bcrypt('password123'),
                'role' => 'agent',
                'phone' => '345-678-9012',
                'bio' => 'Experienced real estate agent helping clients find their dream homes.',
            ],
        ];

        foreach ($users as $userData) {
            $roleName = $userData['role'];
            unset($userData['role']); // Remove role from user data since we'll attach it separately

            $user = User::create(array_merge($userData, [
                'status' => 'active',
                'email_verified_at' => now(),
            ]));

            $role = Role::where('name', $roleName)->firstOrFail();
            $user->roles()->attach($role);
        }
    }
}