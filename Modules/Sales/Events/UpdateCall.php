<?php

namespace Modules\Sales\Events;

use Illuminate\Queue\SerializesModels;

class UpdateCall
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $call;

    public function __construct($request,$call)
    {
        $this->request = $request;
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
