<?php

namespace Modules\Sales\Events;

use Illuminate\Queue\SerializesModels;

class CreateQuote
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $quote;

    public function __construct($request,$quote)
    {
        $this->request = $request;
        $this->quote = $quote;
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
