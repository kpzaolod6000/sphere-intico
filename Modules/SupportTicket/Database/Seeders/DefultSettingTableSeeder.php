<?php

namespace Modules\SupportTicket\Database\Seeders;

use App\Models\User;
use App\Models\WorkSpace;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\SupportTicket\Entities\TicketField;


class DefultSettingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $super_admin = User::where('type','super admin')->first();
        if(!empty($super_admin))
        {
            $companys = User::where('type','company')->get();
            foreach($companys as $company)
            {
                $WorkSpaces = WorkSpace::where('created_by',$company->id)->get();
                foreach($WorkSpaces as $WorkSpace)
                {
                    TicketField::defultadd($company->id,$WorkSpace->id);
                }
            }
        }
    }
}
