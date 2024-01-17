<?php

namespace Modules\Sales\Events;

use Illuminate\Queue\SerializesModels;

class CreateCaseType
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $casetype;
    public function __construct($request, $casetype)
    {
        $this->request         = $request;
        $this->casetype = $casetype;
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
