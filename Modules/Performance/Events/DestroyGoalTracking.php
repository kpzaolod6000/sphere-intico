<?php

namespace Modules\Performance\Events;

use Illuminate\Queue\SerializesModels;

class DestroyGoalTracking
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $goalTracking;
    public function __construct($goalTracking)
    {
        $this->goalTracking = $goalTracking;
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
