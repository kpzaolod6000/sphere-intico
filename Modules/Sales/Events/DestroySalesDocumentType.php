<?php

namespace Modules\Sales\Events;

use Illuminate\Queue\SerializesModels;

class DestroySalesDocumentType
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $salesdocumenttype;
    public function __construct($salesdocumenttype)
    {
        $this->salesdocumenttype = $salesdocumenttype;
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
