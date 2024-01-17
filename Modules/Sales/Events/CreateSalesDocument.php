<?php

namespace Modules\Sales\Events;

use Illuminate\Queue\SerializesModels;

class CreateSalesDocument
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $salesdocument;

    public function __construct($request, $salesdocument)
    {
        $this->request       = $request;
        $this->salesdocument = $salesdocument;
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
