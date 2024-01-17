<?php

namespace Modules\SupportTicket\Events;

use Illuminate\Queue\SerializesModels;

class ReplyPublicTicket
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $conversion;
    public function __construct($request,$conversion)
    {
        $this->request = $request;
        $this->conversion = $conversion;
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
