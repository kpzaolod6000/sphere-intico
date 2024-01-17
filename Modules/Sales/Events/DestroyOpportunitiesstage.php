<?php

namespace Modules\Sales\Events;

use Illuminate\Queue\SerializesModels;

class DestroyOpportunitiesstage
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $opportunitiesStage;
    public function __construct($opportunitiesStage)
    {
        $this->opportunitiesStage = $opportunitiesStage;
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
