<?php

namespace Modules\Workflow\Database\Seeders;

use Illuminate\Database\Seeder;
use Nwidart\Modules\Facades\Module;
use Illuminate\Database\Eloquent\Model;

class WorkflowdothisTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
 
            $check = \Modules\Workflow\Entities\Workflowdothis::where('submodule','Send Email Notification')->first();
            if(!$check){
                $new = new \Modules\Workflow\Entities\Workflowdothis();
                $new->submodule = 'Send Email Notification';
                $new->type = 'company';
                $new->save();
            }
        // $this->call("OthersTableSeeder");
    }
}
