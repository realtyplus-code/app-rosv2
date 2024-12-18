<?php

namespace Database\Seeders\Rols;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'owners']);
        Role::create(['name' => 'tenants']);
        Role::create(['name' => 'providers']);
    }
}
