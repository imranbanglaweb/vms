<?php
  
namespace Database\Seeders;
  
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
  
class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
           'role-list',
           'role-create',
           'role-edit',
           'role-delete',
           'product-list',
           'product-create',
           'product-edit',
           'product-delete',
           'project-list',
           'project-create',
           'project-edit',
           'project-delete',
           'land-list',
           'land-create',
           'land-edit',
           'land-delete',
           'document-type-list',
           'document-type-create',
           'document-type-edit',
           'document-type-delete',
           'document-history-view',
           'document-history-export'
        ];
     
        foreach ($permissions as $permission) {
             Permission::create(['name' => $permission]);
        }
    }
}