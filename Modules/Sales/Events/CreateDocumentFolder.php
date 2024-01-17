<?php

namespace Modules\Sales\Events;

use Illuminate\Queue\SerializesModels;

class CreateDocumentFolder
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $documentfolder;
    public function __construct($request, $documentfolder)
    {
        $this->request         = $request;
        $this->documentfolder = $documentfolder;
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
