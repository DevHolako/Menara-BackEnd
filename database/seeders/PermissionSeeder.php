<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    // auto generate array
    public function generatePermissionsArray(...$prefixes)
    {
        $main = [];

        foreach ($prefixes as $prefix) {
            $permissionNames = [
                "view {$prefix}s",
                "view {$prefix}",
                "store {$prefix}",
                "update {$prefix}",
                "soft-delete {$prefix}",
                "restore {$prefix}",
                "force-delete {$prefix}",
            ];

            $main = array_merge($main, $permissionNames);
        }

        return $main;
    }

    // for generating permissions
    public function createSanctumUserPermissions($permissions)
    {
        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission, 'guard_name' => 'sanctum']);
        }
    }

    public function run(): void
    {

        $tables = ["user", "client", "article", "categorie"];

        // array of perm for each prefix
        $list = $this->generatePermissionsArray(...$tables);

        $this->createSanctumUserPermissions($list);
    }
}
