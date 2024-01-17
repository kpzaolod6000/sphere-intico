<?php

namespace Modules\Sales\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SalesInvoiceItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'item',
        'qty',
        'tax_rate',
        'list_price',
        'unit_price',
        'description',
        'workspace',
        'created_by',
    ];

    public function items()
    {
        if(module_is_active('ProductService')){
            return $this->hasOne(\Modules\ProductService\Entities\ProductService::class, 'id', 'item')->first();
        }
        else
        {
            return [];
        }
    }

    public function taxs()
    {
        return $this->hasOne('\Modules\ProductService\Entities\Tax', 'id', 'tax');
    }

    protected static function newFactory()
    {
        return \Modules\Sales\Database\factories\SalesInvoiceItemFactory::new();
    }

    public function tax($taxes)
    {
        if(module_is_active('ProductService')){
            $taxArr = explode(',', $taxes);
            $taxes = [];
            foreach($taxArr as $tax)
            {
                $taxes[] = \Modules\ProductService\Entities\Tax::find($tax);
            }
            return $taxes;
        }
        else
        {
            return [];
        }
        }
}
