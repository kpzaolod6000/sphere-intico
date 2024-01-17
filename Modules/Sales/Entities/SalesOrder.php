<?php

namespace Modules\Sales\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SalesOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'quote',
        'Opportunity',
        'status',
        'account',
        'amount',
        'date_quoted',
        'quote_number',
        'billing_address',
        'billing_city',
        'billing_state',
        'billing_country',
        'billing_postalcode',
        'shipping_address',
        'shipping_city',
        'shipping_state',
        'shipping_country',
        'shipping_postalcode',
        'billing_contact',
        'shipping_contact',
        'tax',
        'shipping _provider',
        'description',
        'workspace',
        'created_by',
    ];

    protected $appends = [
        'user_id_name',
        'quote_name',
        'opportunity_name',

    ];

    public static $status   = [
        'Open',
        'Cancelled',
    ];

    public function assign_user()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }

    public function opportunitys()
    {
        return $this->hasOne('Modules\Sales\Entities\Opportunities', 'id', 'opportunity');
    }
    public function accounts()
    {
        return $this->hasOne('Modules\Sales\Entities\SalesAccount', 'id', 'account');
    }

    public function contacts()
    {
        return $this->hasOne('Modules\Sales\Entities\Contact', 'id', 'billing_contact');
    }

    public function quotes()
    {

        return $this->hasOne('Modules\Sales\Entities\Quote', 'id', 'quote');
    }

    public function itemsdata()
    {
        return $this->hasMany('Modules\Sales\Entities\SalesOrderItem', 'salesorder_id', 'id');
    }

    public function taxs()
    {
        return $this->hasOne('Modules\ProductService\Entities\ProductTax', 'id', 'tax');
    }
    public function getaccount($type, $id)
    {
        $parent = SalesAccount::find($id)->name;
        return $parent;
    }

    public static function Tax($tax)
    {

        if(module_is_active('ProductService'))
        {
            $taxArr = explode(',', $tax);

            $tax = 0;
            foreach($taxArr as $tax)
            {
                $taxs = \Modules\ProductService\Entities\Tax::find($tax);


                $taxname=$taxs->name;
            }


            return $taxname;
        }
        else
        {
            return 0;
        }


    }
    public static function salesorderNumberFormat($number,$company_id = null,$workspace = null)
    {
        if(!empty($company_id) && empty($workspace)){
            $data = !empty(company_setting('salesorder_prefix',$company_id)) ? company_setting('salesorder_prefix',$company_id) : '#SLO';
        }elseif(!empty($company_id) && !empty($workspace)){
            $data = !empty(company_setting('salesorder_prefix',$company_id,$workspace)) ? company_setting('salesorder_prefix',$company_id,$workspace) : '#SLO';
        }else{
            $data = !empty(company_setting('salesorder_prefix')) ? company_setting('salesorder_prefix') : '#SLO';
        }
        return $data. sprintf("%05d", $number);
    }

    protected static function newFactory()
    {
        return \Modules\Sales\Database\factories\SalesOrderFactory::new();
    }

    public function getSubTotal()
    {
        $subTotal = 0;
        foreach($this->itemsdata as $product)
        {
            $subTotal += ($product->price * $product->quantity);
        }

        return $subTotal;
    }

    public function getTotalTax()
    {
        if(module_is_active('ProductService'))
        {
            $totalTax = 0;
            foreach($this->itemsdata as $product)
            {
                $taxes = SalesUtility::totalTaxRate($product->tax);

                $totalTax += ($taxes / 100) * (($product->price * $product->quantity)-$product->discount);
            }

            return $totalTax;
        }
        else{
            return 0;
        }
    }

    public function getTotalDiscount()
    {
        $totalDiscount = 0;
        foreach($this->itemsdata as $product)
        {
            $totalDiscount += $product->discount;
        }

        return $totalDiscount;
    }

    public function getTotal()
    {
        return ($this->getSubTotal() - $this->getTotalDiscount()+ $this->getTotalTax());
    }

    public function getUserIdNameAttribute()
    {
        $user_id = SalesOrder::find($this->user_id);

        return $this->attributes['user_id_name'] = !empty($user_id) ? $user_id->name : '';
    }

    public function getQuoteNameAttribute()
    {
        $quote = SalesOrder::find($this->quote_id);

        return $this->attributes['quote_name'] = !empty($quote) ? $quote->name : '';
    }

    public function getOpportunityNameAttribute()
    {
        $opportunity = SalesOrder::find($this->opportunity);

        return $this->attributes['opportunity_name'] = !empty($opportunity) ? $opportunity->name : '';
    }

    public static function statuss($status)
    {
        if($status==0)
        {
            $status='Open';
        }
        elseif($status==1)
        {
            $status='Not Paid';
        }
         elseif($status==2)
        {
            $status='Partialy Paid';
        }
         elseif($status==3)
        {
            $status='Paid';
        }
         elseif($status==4)
        {
            $status='Cancelled';
        }


        return $status;

    }


}
