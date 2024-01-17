<?php

namespace Modules\Sales\Events;

use Illuminate\Queue\SerializesModels;

class DestroySalesDocument
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $salesdocument;

    public function __construct($salesdocument)
    {
        $this->salesdocument = $salesdocument;
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
