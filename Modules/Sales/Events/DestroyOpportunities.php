<?php

namespace Modules\Sales\Events;

use Illuminate\Queue\SerializesModels;

class DestroyOpportunities
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $opportunities;
    public function __construct($opportunities)
    {
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
