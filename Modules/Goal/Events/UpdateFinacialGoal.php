<?php

namespace Modules\Goal\Events;

use Illuminate\Queue\SerializesModels;

class UpdateFinacialGoal
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $goal;

    public function __construct($request ,$goal)
    {
        $this->request = $request;
        $this->goal = $goal;
    }
}
