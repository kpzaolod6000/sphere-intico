<?php

namespace Modules\Contract\Database\Seeders;

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

        $check = Sidebar::where('title',__('Contract'))->where('parent_id',0)->exists();
        if(!$check)
        {
            $contract_main = Sidebar::where('title',__('Contract'))->where('parent_id',0)->where('type','company')->first();
            if($contract_main == null)
            {
                $contract_main = Sidebar::create([
                    'title' => 'Contract',
                    'icon' => 'ti ti-device-floppy',
                    'parent_id' => 0,
                    'sort_order' => 410,
                    'route' => '',
                    'permissions' => 'contract manage',
                    'type' => 'company',
                    'module' => 'Contract',
                ]);
            }

            $contract = Sidebar::where('title',__('Contract'))->where('parent_id',$contract_main->id)->where('type','company')->first();
            if($contract == null)
            {
                 Sidebar::create([
                    'title' => 'Contract',
                    'icon' => '',
                    'parent_id' => $contract_main->id,
                    'sort_order' => 10,
                    'route' => 'contract.index',
                    'permissions' => 'contract manage',
                    'type' => 'company',
                    'module' => 'Contract',
                ]);
            }

            $contracttype = Sidebar::where('title',__('Contract Type'))->where('parent_id',$contract_main->id)->where('type','company')->first();
            if($contracttype == null)
            {
                Sidebar::create([
                    'title' => 'Contract Type',
                    'icon' => '',
                    'parent_id' => $contract_main->id,
                    'sort_order' => 15,
                    'route' => 'contract_type.index',
                    'permissions' => 'contracttype manage',
                    'type' => 'company',
                    'module' => 'Contract',
                ]);
            }
        }
    }
}
