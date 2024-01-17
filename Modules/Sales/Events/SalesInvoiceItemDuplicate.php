<?php

namespace Modules\Sales\Events;

use Illuminate\Queue\SerializesModels;

class SalesInvoiceItemDuplicate
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $duplicate;
    public $invoiceItem;
    public function __construct($duplicate, $invoiceItem)
    {
        $this->invoiceItem = $invoiceItem;
        $this->duplicate   = $duplicate;
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
