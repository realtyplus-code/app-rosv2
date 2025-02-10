<?php

namespace Database\Seeders\Users;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminUser = User::create([
            'name' => 'Admin User',
            'user' => 'admin',
            'email' => 'admin@ros.com',
            'password' => bcrypt('password'),
        ]);
        $adminRole = Role::where('name', 'admin')->first();
        $adminUser->assignRole($adminRole);

        $rolUser = User::create([
            'name' => 'Global User',
            'user' => 'global',
            'email' => 'global@ros.com',
            'password' => bcrypt('password'),
        ]);
        $adminRole = Role::where('name', 'global_manager')->first();
        $rolUser->assignRole($adminRole);

        $roles = ['owner', 'tenant'];
        for ($i = 1; $i <= 10; $i++) {
            $user = User::create([
                'name' => "Test User $i",
                'user' => "test$i",
                'email' => "testuser$i@example.com",
                'password' => bcrypt('password'),
            ]);
            $randomRole = $roles[array_rand($roles)];
            $user->assignRole($randomRole);
        }
    }
}
