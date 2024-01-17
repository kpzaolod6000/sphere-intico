<?php

namespace Modules\Sales\Events;

use Illuminate\Queue\SerializesModels;

class CreateOpportunities
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $opportunities;
    public function __construct($request, $opportunities)
    {
        $this->request = $request;
        $this->opportunities = $opportunities;
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
