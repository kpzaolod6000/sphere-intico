<?php

namespace Modules\Sales\Listeners;

use App\Events\DeleteProductService;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Sales\Entities\QuoteItem;
use Modules\Sales\Entities\SalesInvoiceItem;
use Modules\Sales\Entities\SalesOrderItem;

class ProductServiceDelete
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(DeleteProductService $event)
    {
        $quote = QuoteItem::where('item',$event->id)->first();
        $salesinvoice = SalesInvoiceItem::where('item',$event->id)->first();
        $salesorder = SalesOrderItem::where('item',$event->id)->first();
        if(!empty($quote) || !empty($salesinvoice) || !empty($salesorder))
        {
            return 'false';
        }
    }
}
