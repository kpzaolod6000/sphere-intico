<?php

namespace Modules\Goal\Events;

use Illuminate\Queue\SerializesModels;

class DestroyFinacialGoal
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $goal;

    public function __construct($goal)
    {
        $this->goal = $goal;
    }
}
