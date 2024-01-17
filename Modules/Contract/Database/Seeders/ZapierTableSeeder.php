<?php

namespace Modules\Contract\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class ZapierTableSeeder extends Seeder
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
            'New Contract'
        ];

        foreach($sub_module as $sm){    
            $check = \Modules\Zapier\Entities\ZapierModule::where('module','Contract')->where('submodule',$sm)->first();
            if(!$check){
                $new = new \Modules\Zapier\Entities\ZapierModule();
                $new->module = 'Contract';
                $new->submodule = $sm;
                $new->save();
            }
        }
    }
}
