<?php

namespace Modules\CustomField\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Nwidart\Modules\Facades\Module;

class CustomFieldDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->call(SidebarTableSeeder::class);
        $this->call(PermissionTableSeeder::class);
        $this_module = Module::find('CustomField');
        $this_module->enable();
        $modules = Module::all();
        if(module_is_active('CustomField'))
        {
            foreach ($modules as $key => $value) {
                $name = '\Modules\\'.$value->getName();
                $path =   $value->getPath();
                if(file_exists($path.'/Database/Seeders/CustomFieldListTableSeeder.php'))
                {
                    $this->call($name.'\Database\Seeders\CustomFieldListTableSeeder');
                }
            }
        }

        if(module_is_active('LandingPage'))
        {
            $this->call(MarketPlaceSeederTableSeeder::class);
        }
    }
}
