<?php

declare(strict_types=1);

namespace App\Support\Permissions;

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

final class SalesRole
{
    /**
     * Ensure the sales onboarding role and its permissions exist (idempotent).
     */
    public static function ensureExists(): Role
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        foreach (['sellers.review', 'sellers.update_details'] as $permission) {
            Permission::query()->firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }

        $role = Role::query()->firstOrCreate([
            'name' => 'sales',
            'guard_name' => 'web',
        ]);

        $role->syncPermissions(['sellers.review', 'sellers.update_details']);

        return $role;
    }
}
