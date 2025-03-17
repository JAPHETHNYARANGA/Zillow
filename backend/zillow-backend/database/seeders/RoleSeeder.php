<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['name' => 'buyer', 'description' => 'User looking to buy or rent properties'],
            ['name' => 'seller', 'description' => 'User listing properties for sale or rent'],
            ['name' => 'agent', 'description' => 'Real estate agent managing listings'],
            ['name' => 'admin', 'description' => 'Administrator with full access'],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}