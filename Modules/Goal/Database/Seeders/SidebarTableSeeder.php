<?php

namespace Modules\Goal\Database\Seeders;

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

        if(module_is_active('Account'))
        {
            $check = Sidebar::where('title',__('Accounting'))->where('parent_id',0)->where('type','company')->first();
            
            $goal = Sidebar::where('title',__('Finacial Goal'))->where('parent_id',$check->id)->where('type','company')->first();
            if($goal == null)
            {
                Sidebar::create( [
                    'title' => 'Finacial Goal',
                    'icon' => '',
                    'parent_id' => $check->id,
                    'sort_order' => 35,
                    'route' => 'goal.index',
                    'permissions' => 'goal manage',
                    'module' => 'Goal',
                    'type'=>'company',
                ]);
            }
         }
    }
}
