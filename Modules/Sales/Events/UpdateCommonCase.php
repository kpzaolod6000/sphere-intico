<?php

namespace Modules\Sales\Events;

use Illuminate\Queue\SerializesModels;

class UpdateCommonCase
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $commonCase;

    public function __construct($request,$commonCase)
    {
        $this->request = $request;
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
