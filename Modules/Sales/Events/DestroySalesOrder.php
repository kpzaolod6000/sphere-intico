<?php

namespace Modules\Sales\Events;

use Illuminate\Queue\SerializesModels;

class DestroySalesOrder
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $salesOrder;

    public function __construct($salesOrder)
    {
        $this->salesOrder = $salesOrder;
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
