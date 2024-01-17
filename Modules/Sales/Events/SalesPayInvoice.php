<?php

namespace Modules\Sales\Events;

use Illuminate\Queue\SerializesModels;

class SalesPayInvoice
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $invoice;
    public $invoicePayment;
    public function __construct($invoice,$invoicePayment)
    {
        $this->invoice        = $invoice;
        $this->invoicePayment = $invoicePayment;
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
