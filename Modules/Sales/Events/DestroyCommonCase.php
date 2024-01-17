<?php

namespace Modules\Sales\Events;

use Illuminate\Queue\SerializesModels;

class DestroyCommonCase
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $commonCase;

    public function __construct($commonCase)
    {
        $this->commonCase = $commonCase;
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
