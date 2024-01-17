<?php

namespace Modules\Performance\Database\Seeders;

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
      if(module_is_active('Hrm'))
       {
            Model::unguard();
            $permission  = [
                'performance manage',
                'indicator manage',
                'indicator create',
                'indicator edit',
                'indicator delete',
                'indicator show',
                'appraisal manage',
                'appraisal create',
                'appraisal edit',
                'appraisal delete',
                'appraisal show',
                'goaltracking manage',
                'goaltracking create',
                'goaltracking edit',
                'goaltracking delete',
                'goal type manage',
                'goal type create',
                'goal type edit',
                'goal type delete',
                'performancetype manage',
                'performancetype create',
                'performancetype edit',
                'performancetype delete',
                'competencies manage',
                'competencies create',
                'competencies edit',
                'competencies delete',

            ];
            $company_role = Role::where('name','company')->first();
            foreach ($permission as $key => $value)
            {
                $table = Permission::where('name',$value)->where('module','Performance')->exists();
                if(!$table)
                {
                    Permission::create(
                        [
                            'name' => $value,
                            'guard_name' => 'web',
                            'module' => 'Performance',
                            'created_by' => 0,
                            "created_at" => date('Y-m-d H:i:s'),
                            "updated_at" => date('Y-m-d H:i:s')
                        ]
                    );
                    $permission = Permission::findByName($value);
                    $company_role->givePermissionTo($permission);
                }
            }
        }
    }
}
