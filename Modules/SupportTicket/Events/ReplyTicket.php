<?php

namespace Modules\SupportTicket\Events;

use Illuminate\Queue\SerializesModels;

class ReplyTicket
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $conversion;
    public $request;
    public $ticket;

    public function __construct($request,$conversion,$ticket)
    {

        $this->request = $request;
        $this->conversion = $conversion;
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
