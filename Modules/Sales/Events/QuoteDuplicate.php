<?php

namespace Modules\Sales\Events;

use Illuminate\Queue\SerializesModels;

class QuoteDuplicate
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $duplicate;
    public $quoteItem;

    public function __construct($duplicate,$quoteItem)
    {
        $this->duplicate = $duplicate;
        $this->quoteItem = $quoteItem;
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
