<?php

namespace Modules\FormBuilder\Database\Seeders;

use Illuminate\Support\Facades\Artisan;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class PermissionsTableSeeder extends Seeder
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
        if(module_is_active('Lead'))
        {
            $permission  = [
                'formbuilder manage',
                'formbuilder create',

            ];


            $company_role = Role::where('name','company')->first();
             foreach ($permission as $key => $value)
             {
                 $table = Permission::where('name',$value)->where('module','FormBuilder')->exists();
                 if(!$table)
                 {
                     Permission::create(
                         [
                             'name' => $value,
                             'guard_name' => 'web',
                             'module' => 'FormBuilder',
                             'created_by' => 0,
                             "created_at" => date('Y-m-d H:i:s'),
                             "updated_at" => date('Y-m-d H:i:s')
                         ]
                     );
                    }
                    $permission = Permission::findByName($value);
                    $company_role->givePermissionTo($permission);
             }
        }
    }
}
