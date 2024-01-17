<?php

namespace Modules\Sales\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class QuoteItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'quote_id',
        'item',
        'qty',
        'tax_rate',
        'list_price',
        'unit_price',
        'description',
        'workspace',
        'created_by',
    ];

    protected static function newFactory()
    {
        return \Modules\Sales\Database\factories\QuoteItemFactory::new();
    }

    public function items()
    {
        if(module_is_active('ProductService'))
        {
            return $this->hasOne(\Modules\ProductService\Entities\ProductService::class, 'id', 'item')->first();
        }
        else
        {
            return [];
        }
    }

    public function taxs()
    {
        return $this->hasOne('Modules\ProductService\Entities\Tax', 'id', 'tax');
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
