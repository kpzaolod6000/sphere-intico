<?php

namespace Modules\DoubleEntry\Events;

use Illuminate\Queue\SerializesModels;

class CreateJournalAccount
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */

    public $request;
    public $journal;

    public function __construct($request ,$journal)
    {
        $this->request = $request;
        $this->journal = $journal;
    }


}
