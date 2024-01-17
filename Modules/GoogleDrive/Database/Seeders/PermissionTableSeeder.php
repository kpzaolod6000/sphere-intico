<?php

namespace Modules\GoogleDrive\Database\Seeders;

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
            'googledrive manage',
            'googledrive delete',
        ];

        $company = Role::where('name','company')->first();

        foreach ($permission as $key => $value) {
            $p = Permission::where('name', $value)->where('module', 'GoogleDrive')->exists();
            if (!$p) {
                $data = Permission::create(
                    [
                        'name' => $value,
                        'guard_name' => 'web',
                        'module' => 'GoogleDrive',
                        'created_by' => 0,
                        "created_at" => date('Y-m-d H:i:s'),
                        "updated_at" => date('Y-m-d H:i:s')
                    ]
                );
                $company->givePermissionTo($data);
            }
        }
        // $this->call("OthersTableSeeder");
    }
}
