<?php

namespace Modules\FormBuilder\Database\Seeders;

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

        if(module_is_active('Lead'))
        {
            $main = Sidebar::where('title',__('CRM'))->where('parent_id',0)->where('type','company')->first();

            $formbuilder = Sidebar::where('title',__('Form Builder'))->where('parent_id',$main->id)->where('type','company')->first();
            if($formbuilder == null)
            {
                Sidebar::create([
                    'title' => 'Form Builder',
                    'icon' => '',
                    'parent_id' => $main->id,
                    'sort_order' => 25,
                    'route' => 'form_builder.index',
                    'permissions' => 'formbuilder manage',
                    'type' => 'company',
                    'module' => 'FormBuilder',
                ]);
            }
        }
    }
}
