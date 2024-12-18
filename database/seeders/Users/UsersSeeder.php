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
            'email' => 'admin@rol.com',
            'password' => bcrypt('password'),
        ]);
        $adminRole = Role::where('name', 'admin')->first();
        $adminUser->assignRole($adminRole);

        $roles = ['owners', 'tenants', 'providers'];
        for ($i = 1; $i <= 10; $i++) {
            $user = User::create([
                'name' => "Test User $i",
                'email' => "testuser$i@example.com",
                'password' => Hash::make('password'),
            ]);
            $randomRole = $roles[array_rand($roles)];
            $user->assignRole($randomRole);
        }
    }
}
