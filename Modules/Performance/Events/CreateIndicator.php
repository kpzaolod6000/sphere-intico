<?php

namespace Modules\Performance\Events;

use Illuminate\Queue\SerializesModels;

class CreateIndicator
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $indicator;
    public function __construct($request, $indicator)
    {
        $this->request         = $request;
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
