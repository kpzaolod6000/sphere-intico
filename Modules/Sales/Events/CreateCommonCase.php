<?php

namespace Modules\Sales\Events;

use Illuminate\Queue\SerializesModels;

class CreateCommonCase
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $commoncase;
    public $request;

    public function __construct($request,$commoncase)
    {
        $this->request = $request;
        $this->commoncase = $commoncase;
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
