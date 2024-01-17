<?php

namespace Modules\Sales\Events;

use Illuminate\Queue\SerializesModels;

class DestroyShippingProvider
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $shippingProvider;
    public function __construct($shippingProvider)
    {
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
