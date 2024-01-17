<?php

namespace Modules\Performance\Database\Seeders;

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

        if(module_is_active('Hrm'))
        {
            $main = Sidebar::where('title',__('HRM'))->where('parent_id',0)->where('type','company')->first();
            $Performance = Sidebar::where('title',__('Performance'))->where('parent_id',$main->id)->where('type','company')->first();
            if($Performance == null)
            {
                $Performance =  Sidebar::create([
                    'title' => 'Performance',
                    'icon' => '',
                    'parent_id' => $main->id,
                    'sort_order' => 30,
                    'route' => '',
                    'permissions' => 'performance manage',
                    'module' => 'Performance',
                    'type'=>'company',
                ]);
            }
            $indicator = Sidebar::where('title',__('Indicator'))->where('parent_id',$Performance->id)->where('type','company')->first();
            if($indicator == null)
            {
                Sidebar::create([
                    'title' => 'Indicator',
                    'icon' => '',
                    'parent_id' => $Performance->id,
                    'sort_order' => 10,
                    'route' => 'indicator.index',
                    'permissions' => 'indicator manage',
                    'module' => 'Performance',
                    'type'=>'company',
                ]);
            }
            $Appraisal = Sidebar::where('title',__('Appraisal'))->where('parent_id',$Performance->id)->where('type','company')->first();
            if($Appraisal == null)
            {
                Sidebar::create([
                    'title' => 'Appraisal',
                    'icon' => '',
                    'parent_id' => $Performance->id,
                    'sort_order' => 15,
                    'route' => 'appraisal.index',
                    'permissions' => 'appraisal manage',
                    'module' => 'Performance',
                    'type'=>'company',
                ]);
            }
            $goal_tracking = Sidebar::where('title',__('Goal Tracking'))->where('parent_id',$Performance->id)->where('type','company')->first();
            if($goal_tracking == null)
            {
                Sidebar::create([
                    'title' => 'Goal Tracking',
                    'icon' => '',
                    'parent_id' => $Performance->id,
                    'sort_order' => 20,
                    'route' => 'goaltracking.index',
                    'permissions' => 'goaltracking manage',
                    'module' => 'Performance',
                    'type'=>'company',
                ]);
            }
        }
    }
}
