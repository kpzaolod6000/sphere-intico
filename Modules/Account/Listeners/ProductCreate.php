<?php

namespace Modules\Account\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\ProductService\Entities\ProductService;

class ProductCreate
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
    public function handle($event)
    {

        $product_Services = $event->productService;
        $productServices = ProductService::where('id',$event->request->id)->first();
        if(!empty($productServices))
        {
            $productServices->sale_chartaccount_id = $product_Services['sale_chartaccount_id'];
            $productServices->expense_chartaccount_id = $product_Services['expense_chartaccount_id'];
            $productServices->save();
        }
    }
}
