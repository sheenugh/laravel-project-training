<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $teamId = 1;
        $teamKey = config('permission.column_names.team_foreign_key', 'team_id');

        app(PermissionRegistrar::class)->forgetCachedPermissions();
        app(PermissionRegistrar::class)->setPermissionsTeamId($teamId);

        $permissions = [
            'view sub-content',
            'create sub-content',
            'edit sub-content',
            'delete sub-content',
            'view activity logs',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }

        $superadmin = Role::firstOrCreate([
            'name' => 'superadmin',
            'guard_name' => 'web',
            $teamKey => $teamId,
        ]);

        $staff = Role::firstOrCreate([
            'name' => 'staff',
            'guard_name' => 'web',
            $teamKey => $teamId,
        ]);

        $superadmin->syncPermissions($permissions);

        $staff->syncPermissions([
            'view sub-content',
        ]);

        $firstUser = User::first();

        if ($firstUser) {
            $firstUser->assignRole($superadmin);
        }
    }
}
