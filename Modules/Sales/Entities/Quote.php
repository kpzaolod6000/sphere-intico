<?php

namespace Modules\Sales\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Quote extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'opportunity',
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
        'created_by',
        'converted_salesorder_id',
        'workspace',
    ];

    public static $status   = [
        'Open',
        'Cancelled',
    ];
    protected $appends    = [
        'opportunity_name',
        'account_name',
    ];

    protected static function newFactory()
    {
        return \Modules\Sales\Database\factories\QuoteFactory::new();
    }


    public function getOpportunityNameAttribute()
    {
        $opportunity = Opportunities::find($this->opportunity);
        return $this->attributes['opportunity_name'] = !empty($opportunity) ? $opportunity->name : '';
    }

    public function getAccountNameAttribute()
    {
        $account = Quote::find($this->account);

        return $this->attributes['account_name'] = !empty($account) ? $account->name : '';
    }

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

    public function taxs()
    {
        return $this->hasOne('Modules\ProductService\Entities\ProductTax', 'id', 'tax');
    }
    public function getaccount($type, $id)
    {
        $parent = SalesAccount::find($id)->name;

        return $parent;
    }

    public static function quoteNumberFormat($number,$company_id = null,$workspace = null)
    {
        if(!empty($company_id) && empty($workspace)){
            $data = !empty(company_setting('quote_prefix',$company_id)) ? company_setting('quote_prefix',$company_id) : '#QUO';
        }elseif(!empty($company_id) && !empty($workspace)){
            $data = !empty(company_setting('quote_prefix',$company_id,$workspace)) ? company_setting('quote_prefix',$company_id,$workspace) : '#QUO';
        }else{
            $data = !empty(company_setting('quote_prefix')) ? company_setting('quote_prefix') : '#QUO';
        }
        return $data. sprintf("%05d", $number);
    }

    public function itemsdata()
    {
        return $this->hasMany('Modules\Sales\Entities\QuoteItem', 'quote_id', 'id');
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
                $taxes =  SalesUtility::totalTaxRate($product->tax);

                $totalTax += ($taxes / 100) * (($product->price * $product->quantity) - $product->discount);
            }

            return $totalTax;
        }
        else
        {
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
        return ($this->getSubTotal() - $this->getTotalDiscount() + $this->getTotalTax());
    }

    public static function Tax($tax)
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


}
