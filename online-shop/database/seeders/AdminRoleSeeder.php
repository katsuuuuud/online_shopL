<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AdminRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role_admin = Role::firstOrCreate(['name' => 'admin']);
        $permission_manage_users = Permission::create(['name' => 'manage users']);

        $role_admin->givePermissionTo($permission_manage_users);
        $user = User::where('email', 'fine@s.com')->first();
        $user->assignRole($role_admin);
    }
}
