<?php

namespace Modules\Sales\Events;

use Illuminate\Queue\SerializesModels;

class UpdateSalesAccountType
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $salesaccounttype;
    public function __construct($request, $salesaccounttype)
    {
        $this->request = $request;
        $this->salesaccounttype = $salesaccounttype;
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
