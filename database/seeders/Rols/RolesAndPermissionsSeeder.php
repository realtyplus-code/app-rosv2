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
            'view_own_properties',
            'create_incidents',
            'view_own_incidents',
            'download_incident_documents',
            'view_incident_status',
            'register_incidents',
            'view_assigned_incidents',
            'update_assigned_incident_status',
            'upload_documents_to_incidents',
            'access_contact_information',
            'view_managed_properties',
            'create_incidents_for_managed_properties',
            'view_management_reports',
            'download_managed_property_documents',
            'view_assigned_ros_clients',
            'open_manage_assign_incidents',
            'change_incident_status',
            'attach_files_to_incidents',
            'generate_incident_reports',
            'global_access',
            'manage_global_incidents',
            'view_global_reports',
            'assign_managers',
            'configure_roles_and_permissions',
            'manage_users',
            'manage_properties_incidents_insurances',
            'link_ros_clients_to_managers'
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Roles y sus permisos
        $rolesAndPermissions = [
            'owner' => [
                'view_own_properties',
                'create_incidents',
                'view_own_incidents',
                'download_incident_documents'
            ],
            'tenant' => [
                'view_incident_status',
                'register_incidents'
            ],
            'provider' => [
                'view_assigned_incidents',
                'update_assigned_incident_status',
                'upload_documents_to_incidents',
                'access_contact_information'
            ],
            'ros_client' => [
                'view_managed_properties',
                'create_incidents_for_managed_properties',
                'view_management_reports',
                'download_managed_property_documents'
            ],
            'ros_client_manager' => [
                'view_assigned_ros_clients',
                'open_manage_assign_incidents',
                'change_incident_status',
                'attach_files_to_incidents',
                'generate_incident_reports'
            ],
            'global_manager' => [
                'global_access',
                'manage_global_incidents',
                'view_global_reports',
                'assign_managers'
            ],
            'admin' => [
                'configure_roles_and_permissions',
                'manage_users',
                'manage_properties_incidents_insurances',
                'link_ros_clients_to_managers'
            ]
        ];

        foreach ($rolesAndPermissions as $roleName => $rolePermissions) {
            $role = Role::create(['name' => $roleName]);
            $role->givePermissionTo($rolePermissions);
        }
    }
}