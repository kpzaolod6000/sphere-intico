<?php

namespace Modules\Workflow\Events;

use Illuminate\Queue\SerializesModels;

class WorkflowWebhook
{
    public $setting;
    public $details;


    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($setting,$details)
    {
        $this->setting = $setting;
        $this->details = $details;
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
