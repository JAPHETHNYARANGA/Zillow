<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            ['name' => 'view_properties', 'description' => 'View property listings'],
            ['name' => 'create_property', 'description' => 'Create a property listing'],
            ['name' => 'edit_property', 'description' => 'Edit own property listings'],
            ['name' => 'delete_property', 'description' => 'Delete own property listings'],
            ['name' => 'manage_users', 'description' => 'Manage user accounts'],
            ['name' => 'manage_properties', 'description' => 'Manage all properties'],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission['name']], $permission);
        }
    }
}