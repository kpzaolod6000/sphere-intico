<?php

namespace Modules\Sales\Events;

use Illuminate\Queue\SerializesModels;

class CreateOpportunitiesstage
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $opportunitiesstage;
    public function __construct($request, $opportunitiesstage)
    {
        $this->request         = $request;
        $this->opportunitiesstage = $opportunitiesstage;
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
