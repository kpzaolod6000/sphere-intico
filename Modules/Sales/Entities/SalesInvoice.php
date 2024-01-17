<?php

namespace Modules\Sales\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SalesInvoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'invoice_id',
        'name',
        'salesorder',
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

    // protected $appends = [
    //     'opportunity_name',
    //     'account_name',
    //     'salesorder_name',
    //     'quote_name',

    // ];



    public static $statuesColor = [
        'Open' => 'primary',
        'Not Paid' => 'danger',
        'Partialy Paid' => 'warning',
        'Paid' => 'success',
        'Cancelled' => 'info',
    ];



    public static $status = [
        'Open',
        'Not Paid',
        'Partialy Paid',
        'Paid',
        'Cancelled',
    ];

    public function assign_user()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }

    public function accounts()
    {
        return $this->hasOne('Modules\Sales\Entities\SalesAccount', 'id', 'account');
    }


    public function taxs()
    {
        return $this->hasOne('Modules\ProductService\Entities\Tax', 'id', 'tax');
    }

    public function opportunitys()
    {
        return $this->hasOne('Modules\Sales\Entities\Opportunities', 'id', 'opportunity');
    }

    public function contacts()
    {
        return $this->hasOne('Modules\Sales\Entities\Contact', 'id', 'billing_contact');
    }

    public function shipping_providers()
    {
        return $this->hasOne('Modules\Sales\Entities\ShippingProvider', 'id', 'shipping_provider');
    }

    public static function change_status($invoice_id, $status)
    {

        $invoice         = SalesInvoice::find($invoice_id);
        $invoice->status = $status;
        $invoice->update();
    }

    protected static function newFactory()
    {
        return \Modules\Sales\Database\factories\SalesInvoiceFactory::new();
    }

    public static function invoiceNumberFormat($number,$company_id = null,$workspace = null)
    {
        if(!empty($company_id) && empty($workspace)){
            $data = !empty(company_setting('salesinvoice_prefix',$company_id)) ? company_setting('salesinvoice_prefix',$company_id) : '#INV';
        }elseif(!empty($company_id) && !empty($workspace)){
            $data = !empty(company_setting('salesinvoice_prefix',$company_id,$workspace)) ? company_setting('salesinvoice_prefix',$company_id,$workspace) : '#INV';
        }else{
            $data = !empty(company_setting('salesinvoice_prefix')) ? company_setting('salesinvoice_prefix') : '#INV';
        }
        return $data. sprintf("%05d", $number);
    }

    public function itemsdata()
    {
        return $this->hasMany('Modules\Sales\Entities\SalesInvoiceItem', 'invoice_id', 'id');
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

    public function getTotal()
    {
        return ($this->getSubTotal() - $this->getTotalDiscount() + $this->getTotalTax());
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

    public static function Tax($tax)
    {
        if(module_is_active('ProductService'))
        {
            $taxArr = explode(',', $tax);

            $tax = 0;
            foreach($taxArr as $tax)
            {
                $taxs = \Modules\ProductService\Entities\Tax::find($tax);


                if($taxs->tax_name!=NULL)
                {
                    $taxname=$taxs->tax_name;
                }
            }
            return $taxname;
        }
        else
        {
            return 0;
        }
    }
    public function getDue()
    {
        $due = 0;
        foreach($this->payments as $payment)
        {
            $due += $payment->amount;
        }

        return ($this->getTotal() - $due);
    }

    public function payments()
    {
        return $this->hasMany(SalesInvoicePayment::class, 'invoice_id', 'id');
    }
}
