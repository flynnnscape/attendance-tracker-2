<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        // Create roles
        $admin = Role::create(['name' => 'Admin']);
        $head = Role::create(['name' => 'Head']);
        $hr = Role::create(['name' => 'HR']);
        $users = Role::create(['name' => 'Users']);

        // Assign permissions
        $admin->givePermissionTo(Permission::all());

        $head->givePermissionTo([
            'users.view',
            'users.create',
            'users.edit',
            'roles.view',
            'roles.edit',
        ]);

        $hr->givePermissionTo([
            'users.view',
            'users.create',
            'users.edit',
        ]);

        $users->givePermissionTo([
            'users.view',
        ]);
    }
}
