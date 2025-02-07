<?php

namespace Database\Seeders\Rols;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Permisos web
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
            'create_insurances',
            'edit_insurances',
            'list_insurances',
            'delete_insurances',
            // solo lectura
            'read_properties',
            'read_providers',
            'read_insurances',
        ];

        // Permisos providers
        $permissionProviders = [
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
            'create_insurances',
            'edit_insurances',
            'list_insurances',
            'delete_insurances',
            // solo lectura
            'read_properties',
            'read_providers',
            'read_insurances',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        foreach ($permissionProviders as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'providers']);
        }

        // Roles y sus permisos web
        $rolesAndPermissions = [
            'owner' => [
                'list_properties',
                'list_incidents',
                'create_incidents',
                'read_providers',
                'read_insurances',
            ],
            'tenant' => [
                'list_properties',
                'list_incidents',
                'create_incidents',
                'read_providers',
                'read_insurances',
            ],
            'ros_client' => [
                'list_properties',
                'list_incidents'
            ],
            'ros_client_manager' => [
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
                'read_providers',
                'read_insurances',
            ],
            'global_manager' => [
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
                'create_providers',
                'edit_providers',
                'list_providers',
                'delete_providers',
                'create_insurances',
                'edit_insurances',
                'list_insurances',
                'delete_insurances',
                'list_users',
                'create_users',
                'edit_users',
                'delete_users',
                'read_properties',
                'read_providers',
                'read_insurances',
                'export_properties',
            ],
            'admin' => [
                'list_enums',
                'create_enums',
                'edit_enums',
                'delete_enums',
            ]
        ];

        $rolesAndPermissionsProviders = [
            'provider' => [
                'list_providers',
                'list_incidents',
                'edit_incidents',
                'list_incidents_actions',
                'edit_incidents_actions',
                'read_properties',
                'read_providers',
                'read_insurances',
            ],
        ];

        foreach ($rolesAndPermissions as $roleName => $rolePermissions) {
            $role = Role::firstOrCreate(['name' => $roleName]);
            $role->syncPermissions($rolePermissions);
        }

        foreach ($rolesAndPermissionsProviders as $roleName => $rolePermissions) {
            $role = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'providers']);
            $role->syncPermissions($rolePermissions);
        }
    }
}
