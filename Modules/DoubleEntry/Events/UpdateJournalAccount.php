<?php

namespace Modules\DoubleEntry\Events;

use Illuminate\Queue\SerializesModels;

class UpdateJournalAccount
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $journalEntry;

    public function __construct($request ,$journalEntry)
    {
        $this->request = $request;
        $this->journalEntry = $journalEntry;
    }
}
