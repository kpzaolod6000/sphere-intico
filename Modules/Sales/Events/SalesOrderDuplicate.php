<?php

namespace Modules\Sales\Events;

use Illuminate\Queue\SerializesModels;

class SalesOrderDuplicate
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $duplicate;
    public $salesorderItem;

    public function __construct($duplicate,$salesorderItem)
    {
        $this->duplicate = $duplicate;
        $this->salesorderItem = $salesorderItem;
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
