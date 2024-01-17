<?php

namespace Modules\SupportTicket\Events;

use Illuminate\Queue\SerializesModels;

class CreateTicket
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */

    public $request;
    public $ticket;
    public function __construct($request,$ticket)
    {
        $this->request = $request;
        $this->ticket = $ticket;
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
