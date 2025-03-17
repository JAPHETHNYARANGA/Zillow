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
            ['name' => 'homeowner', 'description' => 'User owning properties'],
            ['name' => 'sales agent', 'description' => 'Agent assisting with sales'],
            ['name' => 'broker', 'description' => 'Licensed real estate broker'],
            ['name' => 'admin', 'description' => 'Administrator with full access'],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role['name']], $role);
        }
    }
}