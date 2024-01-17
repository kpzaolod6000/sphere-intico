<?php

namespace Modules\Goal\Providers;

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
        $routes = collect(\Route::getRoutes())->map(function ($route) {
            if ($route->getName() != null) {
                return $route->getName();
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
