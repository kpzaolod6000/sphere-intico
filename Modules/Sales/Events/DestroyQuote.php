<?php

namespace Modules\Sales\Events;

use Illuminate\Queue\SerializesModels;

class DestroyQuote
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $quote;

    public function __construct($quote)
    {
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
