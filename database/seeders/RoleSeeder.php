<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create roles
        $adminRole = Role::create(['name' => 'admin']);
        $userRole = Role::create(['name' => 'user']);

        // Create permissions
        $adminPermissions = [
            'document-list',
            'document-create',
            'document-edit',
            'document-delete',
            'document-history-view',
            'document-history-export'
        ];

        $userPermissions = [
            // Add user-specific permissions here
        ];

        // Assign permissions to roles
        $adminRole->syncPermissions($adminPermissions);
        $userRole->syncPermissions($userPermissions);
    }
} 