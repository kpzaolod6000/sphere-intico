<?php

namespace Modules\Sales\Listeners;

use App\Events\DefaultData;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Sales\Entities\SalesUtility;


class DataDefault
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(DefaultData $event)
    {
        $company_id = $event->company_id;
        $workspace_id = $event->workspace_id;
        $user_module = $event->user_module;
        if(!empty($user_module))
        {
            if (in_array("Sales", $user_module))
            {
                SalesUtility::defaultdata($company_id,$workspace_id);
            }
        }
    }
}

