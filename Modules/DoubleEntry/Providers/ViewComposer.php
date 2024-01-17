<?php

namespace Modules\DoubleEntry\Providers;

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
        view()->composer(['settings*'], function ($view) {
            if(\Auth::check())
            {
                $active_module = ActivatedModule();
                $dependency = explode(',','DoubleEntry');
                if(!empty(array_intersect($dependency,$active_module)))
                {
                    $view->getFactory()->startPush('add_button', view('doubleentry::setting.add_button'));
                    $view->getFactory()->startPush('doubleentry_setting_sidebar', view('doubleentry::setting.sidebar'));
                    $view->getFactory()->startPush('doubleentry_setting_sidebar_div', view('doubleentry::setting.nav_containt_div'));




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
