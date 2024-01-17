<?php

namespace Modules\SupportTicket\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class SupportTicketDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        $this->call(EmailTemplateTableSeeder::class);
        $this->call(NotificationsTableSeeder::class);
        $this->call(DefultSettingTableSeeder::class);
        $this->call(PermissionTableSeeder::class);
        if(module_is_active('AIAssistant'))
        {
            $this->call(AIAssistantTemplateListTableSeeder::class);
        }
        if(module_is_active('LandingPage'))
        {
            $this->call(MarketPlaceSeederTableSeeder::class);
        }
    }
}
