<?php

namespace Modules\Sales\Events;

use Illuminate\Queue\SerializesModels;

class UpdateMeeting
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $meeting;

    public function __construct($request,$meeting)
    {
        $this->request = $request;
        $this->meeting = $meeting;
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
