<?php

namespace Modules\Sales\Events;

use Illuminate\Queue\SerializesModels;

class UpdateCaseType
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $caseType;
    public function __construct($request, $caseType)
    {
        $this->request         = $request;
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
