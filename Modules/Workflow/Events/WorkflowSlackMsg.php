<?php

namespace Modules\Workflow\Events;

use Illuminate\Queue\SerializesModels;

class WorkflowSlackMsg
{
    public $msg;
    public $setting;
    
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($msg,$setting)
    {
        $this->msg = $msg;
        $this->setting = $setting;
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
