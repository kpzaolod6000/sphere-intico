<?php

namespace Modules\Timesheet\Database\Seeders;

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

        $main = Sidebar::where('title',__('Timesheet'))->where('parent_id',0)->where('type','company')->first();
        if($main == null)
        {
            $main = Sidebar::create( [
                'title' => 'Timesheet',
                'icon' => 'ti ti-clock',
                'parent_id' => 0,
                'sort_order' => 400,
                'route' => 'timesheet.index',
                'permissions' => 'timesheet manage',
                'type' => 'company',
                'module' => 'Timesheet',
            ]);
        }
    }
}
