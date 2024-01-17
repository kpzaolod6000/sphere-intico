<?php

namespace Modules\Sales\Events;

use Illuminate\Queue\SerializesModels;

class CreateShippingProvider
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $shippingprovider;
    public function __construct($request,$shippingprovider)
    {
        $this->request         = $request;
        $this->shippingprovider = $shippingprovider;
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
