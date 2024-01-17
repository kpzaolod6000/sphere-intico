<?php

namespace Modules\SupportTicket\Providers;

use App\Models\Notification;
use Illuminate\Support\ServiceProvider;
use Modules\SupportTicket\Entities\TicketField;

class ViewComposer extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */


    public function boot(){

        view()->composer(['plans*','settings*'], function ($view) {
            if(\Auth::check())
            {
                $active_module =  ActivatedModule();
                $dependency = explode(',','SupportTicket');
                if(!empty(array_intersect($dependency,$active_module)))
                {
                    $fields = TicketField::where('workspace_id',getActiveWorkSpace())->where('created_by',\Auth::user()->id)->orderBy('order')->get();
                    if($fields->count() < 1)
                    {
                        TicketField::defultadd();
                    }

                    sleep(1);
                    $view->getFactory()->startPush('support_setting_sidebar', view('supportticket::settings.sidebar'));
                    $view->getFactory()->startPush('support_setting_sidebar_div', view('supportticket::settings.nav_containt_div',compact('fields')));
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
