<?php

namespace Modules\Sales\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SalesOrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'salesorder_id',
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

    protected static function newFactory()
    {
        return \Modules\Sales\Database\factories\SalesOrderItemFactory::new();
    }

    public function tax($taxes)
    {
        $taxArr = explode(',', $taxes);
        $taxes = [];
        foreach($taxArr as $tax)
        {
            $taxes[] = \Modules\ProductService\Entities\Tax::find($tax);
        }
        return $taxes;
    }
}
