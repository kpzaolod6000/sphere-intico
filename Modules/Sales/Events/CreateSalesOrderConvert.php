<?php

namespace Modules\Sales\Events;

use Illuminate\Queue\SerializesModels;

class CreateSalesOrderConvert
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $salesorder;
    public $quotesProduct;

    public function __construct($salesorder, $quotesProduct)
    {
        $this->salesorder = $salesorder;
        $this->quotesProduct = $quotesProduct;
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
