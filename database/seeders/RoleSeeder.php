<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $onwer = Role::create(['name' => 'Owner', 'guard_name' => 'sanctum']);
        $admin = Role::create(['name' => 'Admin', 'guard_name' => 'sanctum']);
        Role::create(['name' => 'User', 'guard_name' => 'sanctum']);

        $permissions = Permission::all();
        $onwer->syncPermissions($permissions);
        $admin->syncPermissions($permissions);
    }
}
