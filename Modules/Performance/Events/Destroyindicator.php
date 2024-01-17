<?php

namespace Modules\Performance\Events;

use Illuminate\Queue\SerializesModels;

class Destroyindicator
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $indicator;
    public function __construct($indicator)
    {
        $this->indicator = $indicator;
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
