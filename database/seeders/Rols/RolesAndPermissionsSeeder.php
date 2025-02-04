<?php

namespace Database\Seeders\Rols;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Permisos
        $permissions = [
            'create_properties',
            'edit_properties',
            'list_properties',
            'delete_properties',
            'create_incidents',
            'edit_incidents',
            'list_incidents',
            'delete_incidents',
            'create_incidents_actions',
            'edit_incidents_actions',
            'list_incidents_actions',
            'delete_incidents_actions',
            'create_users',
            'edit_users',
            'list_users',
            'delete_users',
            'create_enums',
            'edit_enums',
            'list_enums',
            'delete_enums',
            'export_properties',
            'create_providers',
            'edit_providers',
            'list_providers',
            'delete_providers',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Roles y sus permisos
        $rolesAndPermissions = [
            'owner' => [
                'list_properties',
                'list_incidents',
                'create_incidents',
                'edit_incidents',
                'delete_incidents',
                'export_properties',
            ],
            'tenant' => [
                'list_properties',
                'list_incidents',
                'create_incidents',
                'edit_incidents',
                'delete_incidents',
            ],
            'provider' => [
                'list_incidents',
                'edit_incidents',
                'list_incidents_actions',
                'create_incidents_actions',
                'edit_incidents_actions',
                'delete_incidents_actions',
            ],
            'ros_client' => [
                'list_properties',
                'list_incidents',
                'create_properties',
                'edit_properties',
                'delete_properties',
                'export_properties',
            ],
            'ros_client_manager' => [
                'list_users',
                'create_users',
                'edit_users',
                'delete_users',
            ],
            'global_manager' => [
                'list_properties',
                'list_incidents',
                'list_users',
                'create_users',
                'edit_users',
                'delete_users',
            ],
            'admin' => [
                'list_properties',
                'create_properties',
                'edit_properties',
                'delete_properties',
                'list_incidents',
                'create_incidents',
                'edit_incidents',
                'delete_incidents',
                'list_incidents_actions',
                'create_incidents_actions',
                'edit_incidents_actions',
                'delete_incidents_actions',
                'list_users',
                'create_users',
                'edit_users',
                'delete_users',
                'list_enums',
                'create_enums',
                'edit_enums',
                'delete_enums',
            ]
        ];

        foreach ($rolesAndPermissions as $roleName => $rolePermissions) {
            $role = Role::create(['name' => $roleName]);
            //$role = Role::where(['name' => $roleName])->first();
            $role->givePermissionTo($rolePermissions);
        }
    }
}