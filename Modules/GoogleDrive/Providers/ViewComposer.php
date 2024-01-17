<?php

namespace Modules\GoogleDrive\Providers;

use Illuminate\Support\ServiceProvider;

class ViewComposer extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */

     public function boot(){
        view()->composer(['settings*'], function ($view) {
            
            if(\Auth::check())
            {
                $active_module = explode(',', \Auth::user()->active_module);
                $dependency = explode(',', 'GoogleDrive');
                if (!empty(array_intersect($dependency, $active_module))) {

                    $view->getFactory()->startPush('company_setting_aftrer_site_setting_sidebar', view('googledrive::setting.sidebar'));
                    $view->getFactory()->startPush('company_setting_aftrer_site_setting_sidebar_div', view('googledrive::setting.nav_containt_div'));
                }
            }
        });
    }

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
