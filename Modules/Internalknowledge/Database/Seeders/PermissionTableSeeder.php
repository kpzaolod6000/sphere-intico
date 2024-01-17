<?php

namespace Modules\Internalknowledge\Database\Seeders;

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
        $permission  = [
            'internalknowledge manage',
            'book manage',
            'book create',
            'book edit',
            'book delete',
            'book show',
            'article manage',
            'article create',
            'article edit',
            'article delete',
            'article show',
            'article duplicate',
            'my article manage',
        ];

        $company_role = Role::where('name','company')->first();
        foreach ($permission as $key => $value)
        {
            $table = Permission::where('name',$value)->where('module','Internalknowledge')->exists();
            if(!$table)
            {
                Permission::create(
                    [
                        'name' => $value,
                        'guard_name' => 'web',
                        'module' => 'Internalknowledge',
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