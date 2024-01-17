<?php

namespace Modules\SupportTicket\Events;

use Illuminate\Queue\SerializesModels;

class DestroyTicket
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $ticket;
    public function __construct($ticket)
    {
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
