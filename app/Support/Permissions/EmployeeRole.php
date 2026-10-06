<?php

declare(strict_types=1);

namespace App\Support\Permissions;

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

final class EmployeeRole
{
    /**
     * Ensure the employee moderation role and its permissions exist (idempotent).
     */
    public static function ensureExists(): Role
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        foreach (['leads.view', 'leads.create', 'leads.update', 'leads.delete', 'users.manage'] as $permission) {
            Permission::query()->firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }

        $role = Role::query()->firstOrCreate([
            'name' => 'employee',
            'guard_name' => 'web',
        ]);

        $role->syncPermissions(['leads.view', 'leads.create', 'leads.update', 'leads.delete', 'users.manage']);

        return $role;
    }
}
