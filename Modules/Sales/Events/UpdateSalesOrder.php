<?php

namespace Modules\Sales\Events;

use Illuminate\Queue\SerializesModels;

class UpdateSalesOrder
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $salesOrder;

    public function __construct($request,$salesOrder)
    {
        $this->request = $request;
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
