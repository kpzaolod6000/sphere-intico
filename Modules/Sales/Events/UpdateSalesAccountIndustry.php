<?php

namespace Modules\Sales\Events;

use Illuminate\Queue\SerializesModels;

class UpdateSalesAccountIndustry
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $accountIndustry;
    public function __construct($request, $accountIndustry)
    {
        $this->request         = $request;
        $this->accountIndustry = $accountIndustry;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
