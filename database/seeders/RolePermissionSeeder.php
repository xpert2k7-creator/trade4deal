<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'leads.view',
            'leads.create',
            'leads.update',
            'leads.delete',
            'products.manage',
            'seller.profile.manage',
            'users.manage',
            'admin.access',
        ];

        foreach ($permissions as $permission) {
            Permission::query()->firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        $buyer = Role::query()->firstOrCreate(['name' => 'buyer', 'guard_name' => 'web']);
        $seller = Role::query()->firstOrCreate(['name' => 'seller', 'guard_name' => 'web']);
        $admin = Role::query()->firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $employee = Role::query()->firstOrCreate(['name' => 'employee', 'guard_name' => 'web']);

        $buyer->syncPermissions(['leads.view', 'leads.create']);
        $seller->syncPermissions(['leads.view', 'leads.create', 'products.manage', 'seller.profile.manage']);
        $admin->syncPermissions(Permission::all());
        $employee->syncPermissions(['leads.view', 'leads.create', 'leads.update', 'leads.delete', 'users.manage']);
    }
}
