<?php

namespace Modules\Sales\Providers;

use Illuminate\Support\ServiceProvider;

class ViewComposer extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */

    public function boot()
    {
        view()->composer(['settings*'], function ($view)
        {
            if(\Auth::check())
            {
                $active_module =  ActivatedModule();
                $dependency = explode(',','Sales');
                if(!empty(array_intersect($dependency,$active_module)))
                {
                    $view->getFactory()->startPush('sales_setting_sidebar', view('sales::setting.sidebar'));
                    $view->getFactory()->startPush('sales_setting_sidebar_div', view('sales::setting.nav_containt_div'));

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