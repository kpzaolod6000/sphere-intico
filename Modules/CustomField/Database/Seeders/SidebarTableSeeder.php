<?php

namespace Modules\CustomField\Database\Seeders;

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

        $check = Sidebar::where('title',__('Custom Field'))->where('parent_id',0)->first();
        if($check == null)
        {
             Sidebar::create( [
                'title' => __('Custom Field'),
                'icon' => 'ti ti-circle-plus',
                'parent_id' => 0,
                'sort_order' => 390,
                'route' => 'custom-field.index',
                'permissions' => 'customfield manage',
                'module' => 'CustomField',
            ]);
        }
        // $this->call("OthersTableSeeder");
    }
}
