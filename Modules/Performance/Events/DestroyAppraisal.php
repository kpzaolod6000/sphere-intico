<?php

namespace Modules\Performance\Events;

use Illuminate\Queue\SerializesModels;

class DestroyAppraisal
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $appraisal;
    public function __construct($appraisal)
    {
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
