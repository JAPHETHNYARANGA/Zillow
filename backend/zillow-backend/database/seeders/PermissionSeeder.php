<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            ['name' => 'view_properties', 'description' => 'View all properties'],
            ['name' => 'create_property', 'description' => 'Create a new property listing'],
            ['name' => 'edit_property', 'description' => 'Edit own property listings'],
            ['name' => 'delete_property', 'description' => 'Delete own property listings'],
            ['name' => 'manage_users', 'description' => 'Manage all users (view, edit, delete)'],
            ['name' => 'manage_properties', 'description' => 'Manage all properties (view, edit, delete)'],
        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }
    }
}