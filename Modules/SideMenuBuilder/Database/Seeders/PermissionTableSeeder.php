<?php

namespace Modules\SideMenuBuilder\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Artisan;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        Artisan::call('cache:clear');
        $permission = [
            'sidemenubuilder manage',
            'sidemenubuilder create',
            'sidemenubuilder edit',
            'sidemenubuilder delete',
        ];
        $company_role = Role::where('name', 'company')->first();
        foreach ($permission as $key => $value) {
            $table = Permission::where('name', $value)->where('module', 'SideMenuBuilder')->exists();
            if (!$table) {
                Permission::create([
                    'name' => $value,
                    'guard_name' => 'web',
                    'module' => 'SideMenuBuilder',
                    'created_by' => 0,
                    "created_at" => date('Y-m-d H:i:s'),
                    "updated_at" => date('Y-m-d H:i:s')
                ]);
                $permission = Permission::findByName($value);
                $company_role->givePermissionTo($permission);
            }
        }
    }
}
