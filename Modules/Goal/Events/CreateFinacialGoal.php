<?php

namespace Modules\Goal\Events;

use Illuminate\Queue\SerializesModels;

class CreateFinacialGoal
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
