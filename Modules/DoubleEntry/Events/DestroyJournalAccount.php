<?php

namespace Modules\DoubleEntry\Events;

use Illuminate\Queue\SerializesModels;

class DestroyJournalAccount
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $journalEntry;

    public function __construct($journalEntry)
    {
        $this->journalEntry = $journalEntry;
    }
}
