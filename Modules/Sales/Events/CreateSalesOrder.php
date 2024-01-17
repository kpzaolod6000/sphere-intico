<?php

namespace Modules\Sales\Events;

use Illuminate\Queue\SerializesModels;

class CreateSalesOrder
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $salesorder;

    public function __construct($request,$salesorder)
    {
        $this->request = $request;
        $this->salesorder = $salesorder;
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
