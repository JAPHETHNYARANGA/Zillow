<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $rolePermissions = [
            'buyer' => ['view_properties'],
            'seller' => ['view_properties', 'create_property', 'edit_property', 'delete_property'],
            'agent' => ['view_properties', 'create_property', 'edit_property', 'delete_property'],
            'admin' => ['view_properties', 'create_property', 'edit_property', 'delete_property', 'manage_users', 'manage_properties'],
        ];

        foreach ($rolePermissions as $roleName => $permissions) {
            $role = Role::where('name', $roleName)->first();
            if ($role) {
                $permissionIds = Permission::whereIn('name', $permissions)->pluck('id');
                $role->permissions()->sync($permissionIds);
            }
        }
    }
}