<?php

namespace Modules\Sales\Events;

use Illuminate\Queue\SerializesModels;

class UpdateSalesDocumentType
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $salesdocumenttype;
    public function __construct($request, $salesdocumenttype)
    {
        $this->request         = $request;
        $this->salesdocumenttype = $salesdocumenttype;
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
