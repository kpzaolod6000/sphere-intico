<?php

namespace Modules\Workflow\Database\Seeders;

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

        $check = Sidebar::where('title','Workflow')->where('type','company')->exists();
        if(!$check){
            Sidebar::create( [
                'title' => __('Workflow'),
                'icon' => 'ti ti-arrows-split-2',
                'parent_id' => 0,
                'sort_order' => 443,
                'route' => 'workflow.index',
                'module' => 'Workflow',
                'type' => 'company',
                'permissions' => 'workflow manage',
            ]);
        }
        // $this->call("OthersTableSeeder");
    }
}
