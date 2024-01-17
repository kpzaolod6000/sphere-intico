<?php

namespace Modules\Sales\Events;

use Illuminate\Queue\SerializesModels;

class DestroyCaseType
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $caseType;
    public function __construct($caseType)
    {
        $this->caseType = $caseType;
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
