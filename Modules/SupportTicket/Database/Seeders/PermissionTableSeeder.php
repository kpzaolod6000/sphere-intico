<?php

namespace Modules\SupportTicket\Database\Seeders;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Models\Role;
use App\Models\Permission;

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
            'supportticket manage',
            'supportticket dashboard manage',
            'supportticket setup manage',
            'supportticket setting',
            'ticket manage',
            'ticket create',
            'ticket edit',
            'ticket delete',
            'ticket show',
            'ticket reply',
            'knowledgebase manage',
            'knowledgebase create',
            'knowledgebase edit',
            'knowledgebase delete',
            'knowledgebase import',
            'knowledgebasecategory manage',
            'knowledgebasecategory create',
            'knowledgebasecategory edit',
            'knowledgebasecategory delete',
            'faq manage',
            'faq create',
            'faq edit',
            'faq delete',
            'faq import',
            'ticketcategory manage',
            'ticketcategory create',
            'ticketcategory edit',
            'ticketcategory delete',
        ];

        $company_role = Role::where('name','company')->first();
        foreach ($permission as $key => $value)
        {
            $table = Permission::where('name',$value)->where('module','SupportTicket')->exists();
            if(!$table)
            {
                $permission = Permission::create(
                    [
                        'name' => $value,
                        'guard_name' => 'web',
                        'module' => 'SupportTicket',
                        'created_by' => 0,
                        "created_at" => date('Y-m-d H:i:s'),
                        "updated_at" => date('Y-m-d H:i:s')
                    ]
                );
                if(!$company_role->hasPermission($value))
                {
                    $company_role->givePermission($permission);
                }
            }
        }
    }
}
