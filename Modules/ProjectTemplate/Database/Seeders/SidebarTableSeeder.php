<?php

namespace Modules\ProjectTemplate\Database\Seeders;

use App\Models\Sidebar;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class SidebarTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        if(module_is_active('Taskly'))
        {
            $check = Sidebar::where('title',__('Projects'))->where('parent_id',0)->where('type','company')->first();

            $goal = Sidebar::where('title',__('Project Template'))->where('parent_id',$check->id)->where('type','company')->first();
            if($goal == null)
            {
                Sidebar::create( [
                    'title' => 'Project Template',
                    'icon' => '',
                    'parent_id' => $check->id,
                    'sort_order' => 25,
                    'route' => 'project-template.index',
                    'permissions' => 'project template manage',
                    'module' => 'ProjectTemplate',
                    'type'=>'company',
                ]);
            }
        }
    }
}
