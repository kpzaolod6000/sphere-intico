<?php

namespace Modules\Sales\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Artisan;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Modules\Sales\Entities\SalesUtility;

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
                'sales manage',
                'sales dashboard manage',
                'sales setup manage',
                'sales report manage',
                'contact manage',
                'contact create',
                'contact edit',
                'contact delete',
                'contact show',
                'contact import',
                'opportunities manage',
                'opportunities create',
                'opportunities edit',
                'opportunities show',
                'opportunities delete',
                'opportunitiesstage manage',
                'opportunitiesstage create',
                'opportunitiesstage edit',
                'opportunitiesstage delete',
                'salesaccount manage',
                'salesaccount create',
                'salesaccount edit',
                'salesaccount delete',
                'salesaccount show',
                'salesaccount import',
                'salesaccounttype manage',
                'salesaccounttype create',
                'salesaccounttype edit',
                'salesaccounttype delete',
                'accountindustry manage',
                'accountindustry create',
                'accountindustry edit',
                'accountindustry delete',
                'salesdocument manage',
                'salesdocument create',
                'salesdocument edit',
                'salesdocument delete',
                'salesdocument show',
                'salesdocumenttype manage',
                'salesdocumenttype create',
                'salesdocumenttype edit',
                'salesdocumenttype delete',
                'documentfolder manage',
                'documentfolder create',
                'documentfolder edit',
                'documentfolder delete',
                'call manage',
                'call create',
                'call edit',
                'call delete',
                'call show',
                'meeting manage',
                'meeting create',
                'meeting edit',
                'meeting delete',
                'meeting show',
                'stream manage',
                'stream create',
                'stream delete',
                'case manage',
                'case create',
                'case edit',
                'case delete',
                'case show',
                'casetype manage',
                'casetype create',
                'casetype edit',
                'casetype delete',
                'quote manage',
                'quote create',
                'quote edit',
                'quote delete',
                'quote show',
                'quote report',
                'shippingprovider manage',
                'shippingprovider create',
                'shippingprovider edit',
                'shippingprovider delete',
                'salesorder manage',
                'salesorder create',
                'salesorder edit',
                'salesorder delete',
                'salesorder show',
                'salesorder report',
                'salesinvoice manage',
                'salesinvoice create',
                'salesinvoice edit',
                'salesinvoice delete',
                'salesinvoice show',
                'salesinvoice report',
            ];

            $company_role = Role::where('name','company')->first();
            foreach ($permission as $key => $value)
            {
                $table = Permission::where('name',$value)->where('module','Sales')->exists();
                if(!$table)
                {
                    Permission::create(
                        [
                            'name' => $value,
                            'guard_name' => 'web',
                            'module' => 'Sales',
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
