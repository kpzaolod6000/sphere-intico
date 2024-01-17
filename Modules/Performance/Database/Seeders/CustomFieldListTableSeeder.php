<?php

namespace Modules\Performance\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use CustomFieldsModuleList;


class CustomFieldListTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        $sub_module = [
            'Goal Tracking',
        ];
        if(module_is_active('CustomField'))
        {
            foreach($sub_module as $sm){
                $check = \Modules\CustomField\Entities\CustomFieldsModuleList::where('module','Performance')->where('sub_module',$sm)->first();
                if(!$check){
                    $new = new \Modules\CustomField\Entities\CustomFieldsModuleList();
                    $new->module = 'Performance';
                    $new->sub_module = $sm;
                    $new->save();
                }
            }
        }
        // $this->call("OthersTableSeeder");
    }
}
