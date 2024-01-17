<?php

namespace Modules\Sales\Events;

use Illuminate\Queue\SerializesModels;

class UpdateOpportunitiesstage
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $opportunitiesStage;
    public function __construct($request, $opportunitiesStage)
    {
        $this->request         = $request;
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
