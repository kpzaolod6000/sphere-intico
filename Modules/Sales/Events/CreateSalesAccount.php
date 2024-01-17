<?php

namespace Modules\Sales\Events;

use Illuminate\Queue\SerializesModels;

class CreateSalesAccount
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $salesaccount;

    public function __construct($request, $salesaccount)
    {
        $this->request = $request;
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
