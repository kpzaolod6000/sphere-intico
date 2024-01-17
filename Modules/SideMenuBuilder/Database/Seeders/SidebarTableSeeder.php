<?php

namespace Modules\SideMenuBuilder\Database\Seeders;

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

        $check = Sidebar::where('title', 'Side Menu Builder')->where('parent_id', 0)->where('type', 'company')->exists();
        if (!$check) {
            Sidebar::create([
                'title' => __('Side Menu Builder'),
                'icon' => 'ti ti-circle-plus',
                'parent_id' => 0,
                'sort_order' => 472,
                'route' => 'sidemenubuilder.index',
                'module' => 'SideMenuBuilder',
                'type' => 'company',
                'permissions' => 'sidemenubuilder manage',
            ]);
        }

    }
}
