<?php

namespace Modules\Sales\Events;

use Illuminate\Queue\SerializesModels;

class UpdateShippingProvider
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $shippingProvider;
    public function __construct($request, $shippingProvider)
    {
        $this->request         = $request;
        $this->shippingProvider = $shippingProvider;
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
