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
        $user = Role::create(['name' => 'User', 'guard_name' => 'sanctum']);
        $permissions = Permission::all();
        $onwer->syncPermissions($permissions);
    }
}
