<?php

namespace Modules\Sales\Events;

use Illuminate\Queue\SerializesModels;

class DestroyCall
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
   public $call;

    public function __construct($call)
    {
        $this->call = $call;
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
