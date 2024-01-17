<?php

namespace Modules\Sales\Events;

use Illuminate\Queue\SerializesModels;

class UpdateSalesInvoiceItem
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */

     public $invoiceitem;
    public $request;
    public function __construct($invoiceitem,$request)
    {
        $this->invoiceitem = $invoiceitem;
        $this->request = $request;
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
