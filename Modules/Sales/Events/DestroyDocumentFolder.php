<?php

namespace Modules\Sales\Events;

use Illuminate\Queue\SerializesModels;

class DestroyDocumentFolder
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $documentFolder;
    public function __construct($documentFolder)
    {
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
