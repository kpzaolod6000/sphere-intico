<?php

namespace Modules\Performance\Events;

use Illuminate\Queue\SerializesModels;

class CreateAppraisal
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $appraisal;
    public function __construct($request, $appraisal)
    {
        $this->request         = $request;
        $this->appraisal = $appraisal;
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
