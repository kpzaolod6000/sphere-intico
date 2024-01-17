<?php

namespace Modules\SideMenuBuilder\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\SideMenuBuilder\Entities\SideMenuBuilder;

class AddHookServiceProvider extends ServiceProvider
{

    public function boot()
    {
        $routes = collect(\Route::getRoutes())->map(function ($route) {
            if($route->getName() != null){
                return $route->getName();
            }
        });

        view()->composer(['partials.sidebar'], function ($modules) {
            $module = SideMenuBuilder::where('created_by', creatorId())->where('workspace', getActiveWorkSpace())->get();

            $modules->getFactory()->startPush('custom_side_menu', view('sidemenubuilder::layouts.addhook', compact('module')));
        });
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}
