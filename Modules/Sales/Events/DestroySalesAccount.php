<?php

namespace Modules\Sales\Events;

use Illuminate\Queue\SerializesModels;

class DestroySalesAccount
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $salesaccount;

    public function __construct($salesaccount)
    {
        $this->salesaccount = $salesaccount;
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
