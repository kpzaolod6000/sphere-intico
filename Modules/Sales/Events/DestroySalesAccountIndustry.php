<?php

namespace Modules\Sales\Events;

use Illuminate\Queue\SerializesModels;

class DestroySalesAccountIndustry
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $accountIndustry;
    public function __construct($accountIndustry)
    {
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
