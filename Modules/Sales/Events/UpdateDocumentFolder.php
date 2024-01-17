<?php

namespace Modules\Sales\Events;

use Illuminate\Queue\SerializesModels;

class UpdateDocumentFolder
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $documentFolder;
    public function __construct($request, $documentFolder)
    {
        $this->request         = $request;
        $this->documentFolder = $documentFolder;
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
