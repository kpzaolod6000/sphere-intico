<?php

namespace Modules\Sales\Http\Controllers;

use App\Models\BankTransferPayment;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\User;
use App\Models\EmailTemplate;
use Illuminate\Support\Facades\Crypt;
use Rawilk\Settings\Support\Context;
use Modules\Sales\Entities\SalesInvoice;
use Modules\Sales\Entities\SalesUtility;
use Modules\Sales\Entities\SalesAccount;
use Modules\Sales\Entities\Opportunities;
use Modules\Sales\Entities\SalesOrder;
use Modules\Sales\Entities\Quote;
use Modules\Sales\Entities\Contact;
use Modules\Sales\Entities\ShippingProvider;
use Modules\Sales\Entities\Stream;
use Modules\Sales\Entities\SalesInvoiceItem;
use Illuminate\Support\Facades\Cookie;
use Modules\Sales\Entities\SalesInvoicePayment;
use Modules\Sales\Events\CreateSalesInvoice;
use Modules\Sales\Events\CreateSalesInvoiceItem;
use Modules\Sales\Events\DestroySalesInvoice;
use Modules\Sales\Events\SalesInvoiceItemDuplicate;
use Modules\Sales\Events\SalesPayInvoice;
use Modules\Sales\Events\UpdateSalesInvoice;
use Modules\Sales\Events\UpdateSalesInvoiceItem;

class SalesInvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if(\Auth::user()->can('salesinvoice manage'))
        {
            $invoices = SalesInvoice::where('created_by',creatorId())->where('workspace',getActiveWorkSpace())->get();

            return view('sales::salesinvoice.index', compact('invoices'));
        }
        else
        {
            return redirect()->back()->with('error', 'permission Denied');
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create($type,$id)
    {
        if(\Auth::user()->can('salesinvoice create'))
        {
            $user = User::where('workspace_id',getActiveWorkSpace())->emp()->get()->pluck('name', 'id');
            if(module_is_active('ProductService')){
                $tax = \Modules\ProductService\Entities\Tax::where('created_by',creatorId())->get()->where('workspace_id',getActiveWorkSpace())->pluck('name', 'id');
                $tax_count =$tax->count();
                $tax->prepend('No Tax', 0);
            }
            else
            {
                $tax=[0 => 'No Tax'];
                $tax_count =$tax;
            }
            $account = SalesAccount::where('created_by',creatorId())->get()->where('workspace',getActiveWorkSpace())->pluck('name', 'id');
            $account->prepend('--', '');
            $opportunities = Opportunities::where('created_by',creatorId())->where('workspace',getActiveWorkSpace())->get()->pluck('name', 'id');
            $salesorder = SalesOrder::where('created_by',creatorId())->get()->where('workspace',getActiveWorkSpace())->pluck('name', 'id');
            $salesorder->prepend('--', 0);
            $quote = Quote::where('created_by',creatorId())->get()->where('workspace',getActiveWorkSpace())->pluck('name', 'id');
            $quote->prepend('--', 0);
            $status  = SalesInvoice::$status;
            $contact = Contact::where('created_by',creatorId())->get()->where('workspace',getActiveWorkSpace())->pluck('name', 'id');
            $contact->prepend('--', '');
            $shipping_provider = ShippingProvider::where('created_by',creatorId())->where('workspace',getActiveWorkSpace())->get()->pluck('name', 'id');

            if(module_is_active('CustomField')){
                $customFields =  \Modules\CustomField\Entities\CustomField::where('workspace_id',getActiveWorkSpace())->where('module', '=', 'sales')->where('sub_module','sales invoice')->get();
            }else{
                $customFields = null;
            }
            return view('sales::salesinvoice.create', compact('user', 'salesorder', 'quote', 'tax', 'account', 'opportunities', 'status', 'contact', 'shipping_provider', 'type', 'id','customFields','tax_count'));
        }
        else
        {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        if(\Auth::user()->can('salesinvoice create'))
        {
            $validator = \Validator::make(
                $request->all(), [
                   'name' => 'required|max:120',
                   'shipping_postalcode' => 'required',
                   'billing_postalcode' => 'required',
                   'tax' => 'required',
                   'date_quoted' => 'required',
                ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            if(count($request->tax) > 1 && in_array(0, $request->tax))
            {
                return redirect()->back()->with('error', 'Please select valid tax');
            }
            $invoice                        = new SalesInvoice();
            $invoice['user_id']             = $request->user;
            $invoice['invoice_id']          = $this->invoiceNumber();
            $invoice['name']                = $request->name;
            $invoice['salesorder']          = $request->salesorder;
            $invoice['quote']               = $request->quote;
            $invoice['opportunity']         = $request->opportunity;
            $invoice['status']              = 0;
            $invoice['account']             = $request->account;
            $invoice['date_quoted']         = $request->date_quoted;
            $invoice['quote_number']        = $request->quote_number;
            $invoice['billing_address']     = $request->billing_address;
            $invoice['billing_city']        = $request->billing_city;
            $invoice['billing_state']       = $request->billing_state;
            $invoice['billing_country']     = $request->billing_country;
            $invoice['billing_postalcode']  = $request->billing_postalcode;
            $invoice['shipping_address']    = $request->shipping_address;
            $invoice['shipping_city']       = $request->shipping_city;
            $invoice['shipping_state']      = $request->shipping_state;
            $invoice['shipping_country']    = $request->shipping_country;
            $invoice['shipping_postalcode'] = $request->shipping_postalcode;
            $invoice['billing_contact']     = $request->billing_contact;
            $invoice['shipping_contact']    = $request->shipping_contact;
            $invoice['tax']                 = implode(',', $request->tax);
            $invoice['shipping_provider']   = $request->shipping_provider;
            $invoice['description']         = $request->description;
            $invoice['workspace']           = getActiveWorkSpace();
            $invoice['created_by']          = creatorId();
            $invoice->save();

            Stream::create(
                [
                    'user_id' => \Auth::user()->id,
                    'created_by' => creatorId(),
                    'log_type' => 'created',
                    'remark' => json_encode(
                        [
                            'owner_name' => \Auth::user()->username,
                            'title' => 'invoice',
                            'stream_comment' => '',
                            'user_name' => $invoice->name,
                        ]
                    ),
                ]
            );


            if(module_is_active('CustomField'))
            {
                \Modules\CustomField\Entities\CustomField::saveData($invoice, $request->customField);
            }

            if(!empty(company_setting('New Sales Invoice')) && company_setting('New Sales Invoice')  == true)
            {
                $Assign_user_phone = User::where('id',$request->user)->where('workspace_id', '=',  getActiveWorkSpace())->first();

                $uArr = [
                    'invoice_id' => $this->invoiceNumber(),
                    'invoice_client' => $Assign_user_phone->name,
                    'date_quoted' => $request->date_quoted,
                    'invoice_status' => 0,
                    'invoice_sub_total' =>  currency_format_with_sym($invoice->getTotal()) ,
                    'created_at' => $request->created_at,

                ];
                $resp = EmailTemplate::sendEmailTemplate('New Sales Invoice', [$invoice->id => $Assign_user_phone->email], $uArr);
            }

            event(new CreateSalesInvoice($request,$invoice));

            return redirect()->back()->with('success', __('Invoice Successfully Created.'));
        }
        else
        {
            return redirect()->back()->with('error', 'permission Denied');
        }
    }
    public static function invoiceNumber()
    {
        $latest = SalesInvoice::where('created_by',creatorId())->where('workspace',getActiveWorkSpace())->latest()->first();
        if(!$latest)
        {
            return 1;
        }

        return $latest->invoice_id + 1;
    }

    public function getaccount(Request $request)
    {
        $opportunitie = Opportunities::where('id', $request->opportunities_id)->first()->toArray();
        $account      = SalesAccount::find($opportunitie['account'])->toArray();

        return response()->json(
            [
                'opportunitie' => $opportunitie,
                'account' => $account,
            ]
        );
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        if(\Auth::user()->can('salesinvoice show'))
        {
            $invoice = SalesInvoice::find($id);
            $bank_transfer_payments = BankTransferPayment::where('created_by',creatorId())->where('workspace',getActiveWorkSpace())->where('type','salesinvoice')->where('request',$invoice->id)->get();
            $items         = [];
            $totalTaxPrice = 0;
            $totalQuantity = 0;
            $totalRate     = 0;
            $totalDiscount = 0;
            $taxesData     = [];
            foreach($invoice->itemsdata as $item)
            {
                $totalQuantity += $item->quantity;
                $totalRate     += $item->price;
                $totalDiscount += $item->discount;
                $taxes         = SalesUtility::tax($item->tax);

                $itemTaxes = [];
                foreach($taxes as $tax)
                {
                    if(!empty($tax))
                    {
                        $taxPrice            = SalesUtility::taxRate($tax->rate, $item->price, $item->quantity,$item->discount);
                        $totalTaxPrice       += $taxPrice;
                        $itemTax['tax_name'] = $tax->tax_name;
                        $itemTax['tax']      = $tax->name . '%';
                        $itemTax['price']    = currency_format_with_sym($taxPrice);
                        $itemTaxes[]         = $itemTax;
                        if(array_key_exists($tax->name, $taxesData))
                        {
                            $taxesData[$tax->tax_name] = $taxesData[$tax->tax_name] + $taxPrice;
                        }
                        else
                        {
                            $taxesData[$tax->tax_name] = $taxPrice;
                        }
                    }
                    else
                    {
                        $taxPrice            = SalesUtility::taxRate(0, $item->price, $item->quantity,$item->discount);
                        $totalTaxPrice       += $taxPrice;
                        $itemTax['tax_name'] = 'No Tax';
                        $itemTax['tax']      = '';
                        $itemTax['price']    = currency_format_with_sym($taxPrice);
                        $itemTaxes[]         = $itemTax;

                        if(array_key_exists('No Tax', $taxesData))
                        {
                            $taxesData[$itemTax['tax_name']] = $taxesData['No Tax'] + $taxPrice;
                        }
                        else
                        {
                            $taxesData['No Tax'] = $taxPrice;
                        }

                    }

                }

                $item->itemTax = $itemTaxes;
                $items[]       = $item;

            }
            $invoice->items         = $items;
            $invoice->totalTaxPrice = $totalTaxPrice;
            $invoice->totalQuantity = $totalQuantity;
            $invoice->totalRate     = $totalRate;
            $invoice->totalDiscount = $totalDiscount;
            $invoice->taxesData     = $taxesData;

            $company_setting['company_name'] = company_setting('company_name');
            $company_setting['company_email'] = company_setting('company_email');
            $company_setting['company_telephone'] = company_setting('company_telephone');
            $company_setting['company_address'] = company_setting('company_address');
            $company_setting['company_city'] = company_setting('company_city');
            $company_setting['company_state'] = company_setting('company_state');
            $company_setting['company_zipcode'] = company_setting('company_zipcode');
            $company_setting['company_country'] = company_setting('company_country');
            $company_setting['registration_number'] = company_setting('registration_number');
            $company_setting['tax_type'] = company_setting('tax_type');
            $company_setting['vat_number'] = company_setting('vat_number');
            $company_setting['salesinvoice_footer_title'] = company_setting('salesinvoice_footer_title');
            $company_setting['salesinvoice_footer_notes'] = company_setting('salesinvoice_footer_notes');
            $company_setting['salesinvoice_shipping_display'] = company_setting('salesinvoice_shipping_display');

            if(module_is_active('CustomField')){
                $invoice->customField = \Modules\CustomField\Entities\CustomField::getData($invoice, 'sales','sales invoice');
                $customFields      = \Modules\CustomField\Entities\CustomField::where('workspace_id', '=', getActiveWorkSpace())->where('module', '=', 'sales')->where('sub_module','sales invoice')->get();
            }else{
                $customFields = null;
            }

            return view('sales::salesinvoice.view', compact('invoice', 'company_setting','customFields','bank_transfer_payments'));
        }
        else
        {
            return redirect()->back()->with('error', 'permission Denied');
        }
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        if(\Auth::user()->can('salesinvoice edit'))
        {
            $invoice = SalesInvoice::find($id);
            if($invoice){

                $opportunity = Opportunities::where('created_by',creatorId())->where('workspace',getActiveWorkSpace())->get()->pluck('name', 'id');
                $salesorder = SalesOrder::where('created_by',creatorId())->where('workspace',getActiveWorkSpace())->get()->pluck('name', 'id');
                $salesorder->prepend('--', 0);
                $quote = Quote::where('created_by',creatorId())->where('workspace',getActiveWorkSpace())->get()->pluck('name', 'id');
                $quote->prepend('--', 0);
                $account = SalesAccount::where('created_by',creatorId())->where('workspace',getActiveWorkSpace())->get()->pluck('name', 'id');
                $account->prepend('--', '');
                $status          = SalesInvoice::$status;
                $billing_contact = Contact::where('created_by',creatorId())->where('workspace',getActiveWorkSpace())->get()->pluck('name', 'id');
                $billing_contact->prepend('--', '');
                if(module_is_active('ProductService')){
                    $tax = \Modules\ProductService\Entities\Tax::where('created_by',creatorId())->where('workspace_id',getActiveWorkSpace())->get()->pluck('name', 'id');
                    $tax->prepend('No Tax', 0);
                }
                else
                {
                    $tax=[0 => 'No Tax'];
                }
                $shipping_provider = ShippingProvider::where('created_by',creatorId())->where('workspace',getActiveWorkSpace())->get()->pluck('name', 'id');
                $user              = User::where('workspace_id',getActiveWorkSpace())->emp()->get()->pluck('name', 'id');
                $user->prepend('--', 0);
                // get previous user id
                $previous = SalesInvoice::where('id', '<', $invoice->id)->max('id');
                // get next user id
                $next = SalesInvoice::where('id', '>', $invoice->id)->min('id');

                if(module_is_active('CustomField')){
                    $invoice->customField = \Modules\CustomField\Entities\CustomField::getData($invoice, 'sales','sales invoice');
                    $customFields             = \Modules\CustomField\Entities\CustomField::where('workspace_id', '=', getActiveWorkSpace())->where('module', '=', 'sales')->where('sub_module','sales invoice')->get();
                }else{
                    $customFields = null;
                }

                return view('sales::salesinvoice.edit', compact('invoice', 'opportunity', 'status', 'account', 'billing_contact', 'tax', 'shipping_provider', 'user', 'salesorder', 'quote', 'previous', 'next','customFields'));
            }else
            {
                return redirect()->back()->with('error', 'Salesinvoice Not Found.');
            }
        }
        else
        {
            return redirect()->back()->with('error', 'permission Denied');
        }
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request,$id)
    {
        if(\Auth::user()->can('salesinvoice edit'))
        {
            $validator = \Validator::make(
                $request->all(), [
                                   'name' => 'required|max:120',
                                   'shipping_postalcode' => 'required',
                                   'billing_postalcode' => 'required',
                                   'tax' => 'required',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            if(count($request->tax) > 1 && in_array(0, $request->tax))
            {
                return redirect()->back()->with('error', 'Please select valid tax');
            }
            $invoice = SalesInvoice::find($id);
            $invoice['user_id']             = $request->user;
            $invoice['name']                = $request->name;
            $invoice['salesorder']          = $request->salesorder;
            $invoice['quote']               = $request->quote;
            $invoice['opportunity']         = $request->opportunity;
            $invoice['account']             = $request->account;
            $invoice['date_quoted']         = $request->date_quoted;
            $invoice['quote_number']        = $request->quote_number;
            $invoice['billing_address']     = $request->billing_address;
            $invoice['billing_city']        = $request->billing_city;
            $invoice['billing_state']       = $request->billing_state;
            $invoice['billing_country']     = $request->billing_country;
            $invoice['billing_postalcode']  = $request->billing_postalcode;
            $invoice['shipping_address']    = $request->shipping_address;
            $invoice['shipping_city']       = $request->shipping_city;
            $invoice['shipping_state']      = $request->shipping_state;
            $invoice['shipping_country']    = $request->shipping_country;
            $invoice['shipping_postalcode'] = $request->shipping_postalcode;
            $invoice['billing_contact']     = $request->billing_contact;
            $invoice['shipping_contact']    = $request->shipping_contact;
            $invoice['tax']                 = implode(',', $request->tax);
            $invoice['shipping_provider']   = $request->shipping_provider;
            $invoice['description']         = $request->description;
            $invoice['workspace']           = getActiveWorkSpace();
            $invoice['created_by']          = creatorId();
            $invoice->save();

            Stream::create(
                [
                    'user_id' => \Auth::user()->id,
                    'created_by' => creatorId(),
                    'log_type' => 'updated',
                    'remark' => json_encode(
                        [
                            'owner_name' => \Auth::user()->username,
                            'title' => 'invoice',
                            'stream_comment' => '',
                            'user_name' => $invoice->name,
                        ]
                    ),
                ]
            );

            if(module_is_active('CustomField'))
            {
                \Modules\CustomField\Entities\CustomField::saveData($invoice, $request->customField);
            }

            event(new UpdateSalesInvoice($request,$invoice));

            return redirect()->back()->with('success', __('Invoice Successfully Updated.'));
        }
        else
        {
            return redirect()->back()->with('error', 'permission Denied');
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        if(\Auth::user()->can('salesinvoice delete'))
        {
            $invoice = SalesInvoice::find($id);
            if(module_is_active('CustomField'))
            {
                $customFields = \Modules\CustomField\Entities\CustomField::where('module','sales')->where('sub_module','sales invoice')->get();
                foreach($customFields as $customField)
                {
                    $value = \Modules\CustomField\Entities\CustomFieldValue::where('record_id', '=', $id)->where('field_id',$customField->id)->first();
                    if(!empty($value)){
                        $value->delete();
                    }
                }
            }
            event(new DestroySalesInvoice($invoice));
            SalesInvoiceItem::where('invoice_id',$id)->where('created_by',$invoice->created_by)->delete();
            $invoice->delete();

            return redirect()->back()->with('success', __('Invoice Successfully delete.'));
        }
        else
        {
            return redirect()->back()->with('error', 'permission Denied');
        }
    }


    public function saveInvoiceTemplateSettings(Request $request)
    {
        $user = \Auth::user();
        $post = $request->all();
        unset($post['_token']);

        if(isset($post['salesinvoice_template']) && (!isset($post['salesinvoice_color']) || empty($post['salesinvoice_color'])))
        {
            $post['salesinvoice_color'] = "ffffff";
        }
        if($request->salesinvoice_logo)
        {
            $validator = \Validator::make(
                $request->all(), [
                                   'salesinvoice_logo' => 'image|mimes:png',
                               ]
            );

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $invoice_logo         = $user->id . '_salesinvoice_logo.png';

            $validation =[
                'mimes:'.'png',
                'max:'.'20480',
            ];

            if($request->hasFile('salesinvoice_logo'))
            {
                $salesinvoice_logo         = $user->id.'_salesinvoice_logo'.time().'.png';

                $uplaod = upload_file($request,'salesinvoice_logo',$salesinvoice_logo,'salesinvoice_logo');
                if($uplaod['flag'] == 1)
                {
                    $url = $uplaod['url'];
                    $old_salesinvoice_logo = company_setting('salesinvoice_logo');
                    if(!empty($old_salesinvoice_logo) && check_file($old_salesinvoice_logo))
                    {
                        delete_file($old_salesinvoice_logo);
                    }
                }
                else
                {
                    return redirect()->back()->with('error',$uplaod['msg']);
                }
            }
        }
        $userContext = new Context(['user_id' => \Auth::user()->id,'workspace_id'=>getActiveWorkSpace()]);
        \Settings::context($userContext)->set('salesinvoice_template', $request->salesinvoice_template);
        \Settings::context($userContext)->set('salesinvoice_color', !empty($request->salesinvoice_color) ? $request->salesinvoice_color : 'ffffff');
        \Settings::context($userContext)->set('salesinvoice_prefix', $request->salesinvoice_prefix);
        \Settings::context($userContext)->set('salesinvoice_footer_title', $request->salesinvoice_footer_title);
        \Settings::context($userContext)->set('salesinvoice_footer_notes', $request->salesinvoice_footer_notes);
        \Settings::context($userContext)->set('salesinvoice_shipping_display', $request->salesinvoice_shipping_display);
        if($request->salesinvoice_logo)
        {
            \Settings::context($userContext)->set('salesinvoice_logo', $url);
        }

        return redirect()->back()->with('success', __('Invoice Setting successfully updated.'));
    }

    public function previewInvoice($template, $color)
    {
        $objUser  = \Auth::user();
        $invoice  = new SalesInvoice();

        $user               = new \stdClass();
        $user->company_name = '<Company Name>';
        $user->name         = '<Name>';
        $user->email        = '<Email>';
        $user->mobile       = '<Phone>';
        $user->address      = '<Address>';
        $user->country      = '<Country>';
        $user->state        = '<State>';
        $user->city         = '<City>';
        $user->zip          = '<Zip>';
        $user->bill_address = '<Address>';
        $user->bill_country = '<Country>';
        $user->bill_state   = '<State>';
        $user->bill_city    = '<City>';
        $user->bill_zip     = '<Zip>';

        $totalTaxPrice = 0;
        $taxesData     = [];

        $items = [];
        for($i = 1; $i <= 3; $i++)
        {
            $item           = new \stdClass();
            $item->name     = 'Item ' . $i;
            $item->quantity = 1;
            $item->tax      = 5;
            $item->discount = 50;
            $item->price    = 100;
            $item->description    = 'In publishing and graphic design, Lorem ipsum is a placeholder';

            $taxes = [
                'Tax 1',
                'Tax 2',
            ];

            $itemTaxes = [];
            foreach($taxes as $k => $tax)
            {
                $taxPrice         = 10;
                $totalTaxPrice    += $taxPrice;
                $itemTax['name']  = 'Tax ' . $k;
                $itemTax['rate']  = '10 %';
                $itemTax['price'] = '$10';
                $itemTaxes[]      = $itemTax;
                if(array_key_exists('Tax ' . $k, $taxesData))
                {
                    $taxesData['Tax ' . $k] = $taxesData['Tax 1'] + $taxPrice;
                }
                else
                {
                    $taxesData['Tax ' . $k] = $taxPrice;
                }
            }
            $item->itemTax = $itemTaxes;
            $items[]       = $item;
        }

        $invoice->invoice_id = 1;
        $invoice->issue_date = date('Y-m-d H:i:s');
        $invoice->due_date   = date('Y-m-d H:i:s');
        $invoice->items      = $items;

        $invoice->totalTaxPrice = 60;
        $invoice->totalQuantity = 3;
        $invoice->totalRate     = 300;
        $invoice->totalDiscount = 10;
        $invoice->taxesData     = $taxesData;


        $preview    = 1;
        $color      = '#' . $color;
        $font_color = SalesUtility::getFontColor($color);

        $dark_logo    = get_file(sidebar_logo());
        $invoice_logo = company_setting('salesinvoice_logo');
        if(isset($invoice_logo) && !empty($invoice_logo))
        {
            $img = get_file($invoice_logo);
        }
        else
        {
            $img = $dark_logo;
        }

        $settings['site_rtl'] = company_setting('site_rtl');
        $settings['company_name'] = company_setting('company_name');
        $settings['company_email'] = company_setting('company_email');
        $settings['company_telephone'] = company_setting('company_telephone');
        $settings['company_address'] = company_setting('company_address');
        $settings['company_city'] = company_setting('company_city');
        $settings['company_state'] = company_setting('company_state');
        $settings['company_zipcode'] = company_setting('company_zipcode');
        $settings['company_country'] = company_setting('company_country');
        $settings['registration_number'] = company_setting('registration_number');
        $settings['tax_type'] = company_setting('tax_type');
        $settings['vat_number'] = company_setting('vat_number');
        $settings['salesinvoice_footer_title'] = company_setting('salesinvoice_footer_title');
        $settings['salesinvoice_footer_notes'] = company_setting('salesinvoice_footer_notes');
        $settings['salesinvoice_shipping_display'] = company_setting('salesinvoice_shipping_display');
        $settings['salesinvoice_template'] = company_setting('salesinvoice_template');
        $settings['salesinvoice_color'] = company_setting('salesinvoice_color');


        return view('sales::salesinvoice.templates.' . $template, compact('invoice', 'preview', 'color', 'img', 'settings', 'user', 'font_color'));
    }

    public function pdf($id)
    {
        $invoiceId = Crypt::decrypt($id);
        $invoice   = SalesInvoice::where('id', $invoiceId)->first();

        $data  = \DB::table('settings');
        $data  = $data->where('id', '=', $invoice->created_by);
        $data1 = $data->get();


        $user         = new User();
        $user->name   = $invoice->name;
        $user->email  = $invoice->contacts->email ?? '';
        $user->mobile = $invoice->contacts->phone ?? '';

        $user->bill_address = $invoice->billing_address;
        $user->bill_zip     = $invoice->billing_postalcode;
        $user->bill_city    = $invoice->billing_city;
        $user->bill_country = $invoice->billing_country;
        $user->bill_state   = $invoice->billing_state;

        $user->address = $invoice->shipping_address;
        $user->zip     = $invoice->shipping_postalcode;
        $user->city    = $invoice->shipping_city;
        $user->country = $invoice->shipping_country;
        $user->state   = $invoice->shipping_state;


        $items         = [];
        $totalTaxPrice = 0;
        $totalQuantity = 0;
        $totalRate     = 0;
        $totalDiscount = 0;
        $taxesData     = [];

        foreach($invoice->itemsdata as $product)
        {
            $product_item = \Modules\ProductService\Entities\ProductService::where('id',$product->item)->first();


            $item           = new \stdClass();
            $item->name     = $product_item->name;
            $item->quantity = $product->quantity;
            $item->tax      = $product->tax;
            $item->discount = $product->discount;
            $item->price    = $product->price;
            $item->description = $product->description;

            $totalQuantity += $item->quantity;
            $totalRate     += $item->price;
            $totalDiscount += $item->discount;

            $taxes     = SalesUtility::tax($product->tax);
            $itemTaxes = [];
            if (!empty($item->tax))
            {
                foreach($taxes as $tax)
                {
                    $taxPrice      = SalesUtility::taxRate($tax->rate, $item->price, $item->quantity,$item->discount);
                    $totalTaxPrice += $taxPrice;

                    $itemTax['name']  = $tax->name;
                    $itemTax['rate']  = $tax->rate . '%';
                    $itemTax['price'] = currency_format_with_sym($taxPrice,$invoice->created_by,$invoice->workspace);
                    $itemTaxes[]      = $itemTax;


                    if(array_key_exists($tax->name, $taxesData))
                    {
                        $taxesData[$tax->name] = $taxesData[$tax->name] + $taxPrice;
                    }
                    else
                    {
                        $taxesData[$tax->name] = $taxPrice;
                    }

                }
            }else
            {
                $item->itemTax = [];
            }
            $item->itemTax = $itemTaxes;
            $items[]       = $item;
        }
        $invoice->issue_date=$invoice->date_quoted;
        $invoice->items         = $items;
        $invoice->totalTaxPrice = $totalTaxPrice;
        $invoice->totalQuantity = $totalQuantity;
        $invoice->totalRate     = $totalRate;
        $invoice->totalDiscount = $totalDiscount;
        $invoice->taxesData     = $taxesData;

        $dark_logo    = get_file(sidebar_logo());
        $invoice_logo = company_setting('salesinvoice_logo',$invoice->created_by,$invoice->workspace);
        if(isset($invoice_logo) && !empty($invoice_logo))
        {
            $img = get_file($invoice_logo);
        }
        else
        {
            $img = $dark_logo;
        }
        $settings['site_rtl'] = company_setting('site_rtl',$invoice->created_by,$invoice->workspace);
        $settings['company_name'] = company_setting('company_name',$invoice->created_by,$invoice->workspace);
        $settings['company_email'] = company_setting('company_email',$invoice->created_by,$invoice->workspace);
        $settings['company_telephone'] = company_setting('company_telephone',$invoice->created_by,$invoice->workspace);
        $settings['company_address'] = company_setting('company_address',$invoice->created_by,$invoice->workspace);
        $settings['company_city'] = company_setting('company_city',$invoice->created_by,$invoice->workspace);
        $settings['company_state'] = company_setting('company_state',$invoice->created_by,$invoice->workspace);
        $settings['company_zipcode'] = company_setting('company_zipcode',$invoice->created_by,$invoice->workspace);
        $settings['company_country'] = company_setting('company_country',$invoice->created_by,$invoice->workspace);
        $settings['registration_number'] = company_setting('registration_number',$invoice->created_by,$invoice->workspace);
        $settings['tax_type'] = company_setting('tax_type',$invoice->created_by,$invoice->workspace);
        $settings['vat_number'] = company_setting('vat_number',$invoice->created_by,$invoice->workspace);
        $settings['salesinvoice_footer_title'] = company_setting('salesinvoice_footer_title',$invoice->created_by,$invoice->workspace);
        $settings['salesinvoice_footer_notes'] = company_setting('salesinvoice_footer_notes',$invoice->created_by,$invoice->workspace);
        $settings['salesinvoice_shipping_display'] = company_setting('salesinvoice_shipping_display',$invoice->created_by,$invoice->workspace);
        $settings['salesinvoice_template'] = company_setting('salesinvoice_template',$invoice->created_by,$invoice->workspace);
        $settings['salesinvoice_color'] = company_setting('salesinvoice_color',$invoice->created_by,$invoice->workspace);

        if(module_is_active('CustomField')){
            $invoice->customField = \Modules\CustomField\Entities\CustomField::getData($invoice, 'sales','sales invoice');
            $customFields      = \Modules\CustomField\Entities\CustomField::where('workspace_id', '=', getActiveWorkSpace($invoice->created_by))->where('module', '=', 'sales')->where('sub_module','sales invoice')->get();
        }else{
            $customFields = null;
        }

        if($invoice)
        {
            $color      = '#' . $settings['salesinvoice_color'];
            $font_color = SalesUtility::getFontColor($color);
            return view('sales::salesinvoice.templates.' . $settings['salesinvoice_template'], compact('invoice', 'user', 'color', 'settings', 'img', 'font_color','customFields'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }

    }

    public function payinvoice($invoice_id)
    {
        if(!empty($invoice_id))
        {
            try {
                $id = \Illuminate\Support\Facades\Crypt::decrypt($invoice_id);
            } catch (\Throwable $th) {
                return redirect('login');
            }

            $invoice = SalesInvoice::where('id',$id)->first();

            if(!is_null($invoice)){


                $items         = [];
                $totalTaxPrice = 0;
                $totalQuantity = 0;
                $totalRate     = 0;
                $totalDiscount = 0;
                $taxesData     = [];

                foreach($invoice->itemsdata as $item)
                {
                    $totalQuantity += $item->quantity;
                    $totalRate     += $item->price;
                    $totalDiscount += $item->discount;
                    $taxes         = SalesUtility::tax($item->tax);

                    $itemTaxes = [];
                    foreach($taxes as $tax)
                    {
                        if(!empty($tax))
                        {

                            $taxPrice            = SalesUtility::taxRate($tax->rate, $item->price, $item->quantity);
                            $totalTaxPrice       += $taxPrice;
                            $itemTax['tax_name'] = $tax->tax_name;
                            $itemTax['tax']      = $tax->rate . '%';
                            $itemTax['price']    = company_date_formate($taxPrice,$invoice->created_by,$invoice->workspace);
                            $itemTaxes[]         = $itemTax;

                            if(array_key_exists($tax->tax_name, $taxesData))
                            {
                                $taxesData[$itemTax['tax_name']] = $taxesData[$tax->tax_name] + $taxPrice;
                            }
                            else
                            {
                                $taxesData[$tax->tax_name] = $taxPrice;
                            }
                        }
                        else
                        {
                            $taxPrice            = SalesUtility::taxRate(0, $item->price, $item->quantity);
                            $totalTaxPrice       += $taxPrice;
                            $itemTax['tax_name'] = 'No Tax';
                            $itemTax['tax']      = '';
                            $itemTax['price']    = company_date_formate($taxPrice,$invoice->created_by,$invoice->workspace);
                            $itemTaxes[]         = $itemTax;

                            if(array_key_exists('No Tax', $taxesData))
                            {
                                $taxesData = $taxesData['No Tax'] + $taxPrice;
                            }
                            else
                            {
                                $taxesData['No Tax'] = $taxPrice;
                            }

                        }
                    }
                    $item->itemTax = $itemTaxes;
                    $items[]       = $item;
                }
                $invoice->items         = $items;
                $invoice->totalTaxPrice = $totalTaxPrice;
                $invoice->totalQuantity = $totalQuantity;
                $invoice->totalRate     = $totalRate;
                $invoice->totalDiscount = $totalDiscount;
                $invoice->taxesData     = $taxesData;

                $ownerId = SalesUtility::ownerIdforInvoice($invoice->created_by);

                $company_setting['company_name'] = company_setting('company_name',$invoice->created_by,$invoice->workspace);
                $company_setting['company_email'] = company_setting('company_email',$invoice->created_by,$invoice->workspace);
                $company_setting['company_telephone'] = company_setting('company_telephone',$invoice->created_by,$invoice->workspace);
                $company_setting['company_address'] = company_setting('company_address',$invoice->created_by,$invoice->workspace);
                $company_setting['company_city'] = company_setting('company_city',$invoice->created_by,$invoice->workspace);
                $company_setting['company_state'] = company_setting('company_state',$invoice->created_by,$invoice->workspace);
                $company_setting['company_zipcode'] = company_setting('company_zipcode',$invoice->created_by,$invoice->workspace);
                $company_setting['company_country'] = company_setting('company_country',$invoice->created_by,$invoice->workspace);
                $company_setting['registration_number'] = company_setting('registration_number',$invoice->created_by,$invoice->workspace);
                $company_setting['tax_type'] = company_setting('tax_type',$invoice->created_by,$invoice->workspace);
                $company_setting['vat_number'] = company_setting('vat_number',$invoice->created_by,$invoice->workspace);
                $company_setting['salesinvoice_footer_title'] = company_setting('salesinvoice_footer_title',$invoice->created_by,$invoice->workspace);
                $company_setting['salesinvoice_footer_notes'] = company_setting('salesinvoice_footer_notes',$invoice->created_by,$invoice->workspace);
                $company_setting['salesinvoice_shipping_display'] = company_setting('salesinvoice_shipping_display',$invoice->created_by,$invoice->workspace);

                $users = User::where('id',$invoice->created_by)->first();

                if(!is_null($users)){
                    \App::setLocale($users->lang);
                }else{
                    $users = User::where('type','company')->first();
                    \App::setLocale($users->lang);
                }


                if(module_is_active('CustomField')){
                    $invoice->customField = \Modules\CustomField\Entities\CustomField::getData($invoice, 'sales','sales invoice');
                    $customFields      = \Modules\CustomField\Entities\CustomField::where('workspace_id', '=', getActiveWorkSpace($invoice->created_by))->where('module', '=', 'sales')->where('sub_module','sales invoice')->get();
                }else{
                    $customFields = null;
                }

                $invoicePayment= SalesInvoicePayment::where("invoice_id",$invoice->id)->first();

                $company_id=$invoice->created_by;
                $workspace = $invoice->workspace;
            event(new SalesPayInvoice($invoice,$invoicePayment));

                return view('sales::salesinvoice.invoicepay',compact('invoice', 'company_setting','users','company_id','workspace','customFields'));
            }else{
                return abort('404', 'The Link You Followed Has Expired');
            }
        }else{
            return abort('404', 'The Link You Followed Has Expired');
        }
    }

    public function invoicelink($invoice_id)
    {

        return view('sales::salesinvoice.invoicelink',compact('invoice_id'));
    }

    public function sendmail(Request $request,$id)
    {
        $validator = \Validator::make(
            $request->all(), [
                                'name' => 'required|max:120',
                                'email' => 'required|email'
                           ]
        );
        if($validator->fails())
        {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }

        $invoice = SalesInvoice::where('id',$id)->first();
        if(!is_null($invoice)){
            $invoice->reciverName = $request->name;
            $invoice->invoice = SalesInvoice::invoiceNumberFormat($invoice->invoice_id);

            $invoiceId    = Crypt::encrypt($invoice->id);
            $invoice->url = route('pay.salesinvoice', $invoiceId);

            $invoice->invoice = SalesInvoice::invoiceNumberFormat($invoice->invoice_id);
            $invoice->url = route('pay.salesinvoice',\Illuminate\Support\Facades\Crypt::encrypt($invoice->id));
            $invoice->reciverName = $request->name;

            if(!empty(company_setting('Sales Invoice Sent')) && company_setting('Sales Invoice Sent')  == true)
            {
                $uArr = [
                    'invoice_recivername' => $invoice->reciverName,
                    'salesinvoice_number' => $invoice->invoice,
                    'salesinvoice_url' => $invoice->url,
                ];
                $resp = EmailTemplate::sendEmailTemplate('Sales Invoice Sent', [$request->email],$uArr);
                return redirect()->back()->with('success', __('Sales Invoice successfully sent.') . ((isset($resp['error'])) ? '<br> <span class="text-danger">' . $resp['error'] . '</span>' : ''));
            }
            else{

                return redirect()->back()->with('error', __('Sales invoice sent notification is off'));
            }
        }else{
            return redirect()->back()->with('error', 'permission Denied');
        }
    }

    public function invoiceitem($id)
    {
        $invoice = SalesInvoice::find($id);

        $items = \Modules\ProductService\Entities\ProductService::where('created_by',creatorId())->where('workspace_id',getActiveWorkSpace())->get()->pluck('name', 'id');
        $items->prepend('select', '');
        $tax_rate = \Modules\ProductService\Entities\Tax::where('created_by', creatorId())->where('workspace_id',getActiveWorkSpace())->get()->pluck('rate', 'id');
        return view('sales::salesinvoice.invoiceitem', compact('items', 'invoice', 'tax_rate'));
    }

    public function storeitem(Request $request, $id)
    {
        $validator = \Validator::make(
            $request->all(), [

                           ]
        );
        if($validator->fails())
        {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }
        $invoice = SalesInvoice::find($id);
        if($invoice->getTotal() == 0.0)
        {
            SalesInvoice::change_status($invoice->id, 0);
        }
        $invoiceitem                = new SalesInvoiceItem();
        $invoiceitem['invoice_id']  = $request->id;
        $invoiceitem['item']        = $request->item;
        $invoiceitem['quantity']    = $request->quantity;
        $invoiceitem['price']       = $request->price;
        $invoiceitem['discount']    = $request->discount;
        $invoiceitem['tax']         = $request->tax;
        $invoiceitem['description'] = $request->description;
        $invoiceitem['workspace']   = getActiveWorkSpace();
        $invoiceitem['created_by']  = creatorId();
        $invoiceitem->save();
        $invoice = SalesInvoice::find($id);
        $invoice_getdue = number_format((float)$invoice->getDue(), 2, '.', '');
        if($invoice_getdue > 0.0 || $invoice_getdue < 0.0)
        {
            SalesInvoice::change_status($invoice->id, 1);
        }
        event(new CreateSalesInvoiceItem($invoice,$request,$invoiceitem));

        return redirect()->back()->with('success', __('Invoice Item Successfully Created.'));

    }

    public function items(Request $request)
    {
        $items        = \Modules\ProductService\Entities\ProductService::where('id', $request->item_id)->first();
        $items->taxes = $items->tax($items->tax_id);

        return json_encode($items);
    }

    public function invoiceItemEdit($id)
    {
        $invoiceItem = SalesInvoiceItem::find($id);

        $items = \Modules\ProductService\Entities\ProductService::where('created_by', creatorId())->where('workspace_id',getActiveWorkSpace())->get()->pluck('name', 'id');
        $items->prepend('select', '');
        $tax_rate = \Modules\ProductService\Entities\Tax::where('created_by',creatorId())->where('workspace_id',getActiveWorkSpace())->get()->pluck('rate', 'id');

        return view('sales::salesinvoice.invoiceitemEdit', compact('items', 'invoiceItem', 'tax_rate'));
    }

    public function invoiceItemUpdate(Request $request, $id)
    {
        $validator = \Validator::make(
            $request->all(), [

                           ]
        );
        if($validator->fails())
        {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }
        $invoiceitem                = SalesInvoiceItem::find($id);
        $invoiceitem['item']        = $request->item;
        $invoiceitem['quantity']    = $request->quantity;
        $invoiceitem['price']       = $request->price;
        $invoiceitem['discount']    = $request->discount;
        $invoiceitem['tax']         = $request->tax;
        $invoiceitem['description'] = $request->description;
        $invoiceitem->save();
        event(new UpdateSalesInvoiceItem($invoiceitem,$request));

        return redirect()->back()->with('success', __('Invoice Item Successfully Updated.'));

    }

    public function itemsDestroy($id)
    {

        $item = SalesInvoiceItem::find($id);
        $invoice = SalesInvoice::find($item->invoice_id);
        $item->delete();
        $invoice_getdue = number_format((float)$invoice->getDue(), 2, '.', '');
        if($invoice_getdue <= 0.0)
        {
            SalesInvoice::change_status($invoice->id, 3);
        }

        return redirect()->back()->with('success', __('Item Successfully delete.'));
    }

    public function duplicate($id)
    {
        if(\Auth::user()->can('salesinvoice create'))
        {
            $invoice                          = SalesInvoice::find($id);
            $duplicate                        = new SalesInvoice();
            $duplicate['user_id']             = $invoice->user_id;
            $duplicate['invoice_id']          = $this->invoiceNumber();
            $duplicate['name']                = $invoice->name;
            $duplicate['salesorder']          = $invoice->salesorder;
            $duplicate['quote']               = $invoice->quote;
            $duplicate['opportunity']         = $invoice->opportunity;
            $duplicate['status']              = 0;
            $duplicate['account']             = $invoice->account;
            $duplicate['amount']              = $invoice->amount;
            $duplicate['date_quoted']         = $invoice->date_quoted;
            $duplicate['quote_number']        = $invoice->quote_number;
            $duplicate['billing_address']     = $invoice->billing_address;
            $duplicate['billing_city']        = $invoice->billing_city;
            $duplicate['billing_state']       = $invoice->billing_state;
            $duplicate['billing_country']     = $invoice->billing_country;
            $duplicate['billing_postalcode']  = $invoice->billing_postalcode;
            $duplicate['shipping_address']    = $invoice->shipping_address;
            $duplicate['shipping_city']       = $invoice->shipping_city;
            $duplicate['shipping_state']      = $invoice->shipping_state;
            $duplicate['shipping_country']    = $invoice->shipping_country;
            $duplicate['shipping_postalcode'] = $invoice->shipping_postalcode;
            $duplicate['billing_contact']     = $invoice->billing_contact;
            $duplicate['shipping_contact']    = $invoice->shipping_contact;
            $duplicate['tax']                 = $invoice->tax;
            $duplicate['shipping_provider']   = $invoice->shipping_provider;
            $duplicate['description']         = $invoice->description;
            $duplicate['workspace']           = getActiveWorkSpace();
            $duplicate['created_by']          = creatorId();
            $duplicate->save();

            Stream::create(
                [
                    'user_id' => \Auth::user()->id,
                    'created_by' => creatorId(),
                    'log_type' => 'created',
                    'remark' => json_encode(
                        [
                            'owner_name' => \Auth::user()->username,
                            'title' => 'invoice',
                            'stream_comment' => '',
                            'user_name' => $invoice->name,
                        ]
                    ),
                ]
            );

            if($duplicate)
            {
                $invoiceItem = SalesInvoiceItem::where('invoice_id', $invoice->id)->get();

                foreach($invoiceItem as $item)
                {

                    $invoiceitem                = new SalesInvoiceItem();
                    $invoiceitem['invoice_id']  = $duplicate->id;
                    $invoiceitem['item']        = $item->item;
                    $invoiceitem['quantity']    = $item->quantity;
                    $invoiceitem['price']       = $item->price;
                    $invoiceitem['discount']    = $item->discount;
                    $invoiceitem['tax']         = $item->tax;
                    $invoiceitem['description'] = $item->description;
                    $invoiceitem['workspace']   = getActiveWorkSpace();
                    $invoiceitem['created_by']  = creatorId();
                    $invoiceitem->save();
                }
            }
            event(new SalesInvoiceItemDuplicate($duplicate, $invoiceItem));

            return redirect()->back()->with('success', __('Invoice duplicate successfully.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function transactionNumber($id)
    {
        $latest = SalesInvoicePayment::select('sales_invoice_payments.*')->join('sales_invoices', 'sales_invoice_payments.invoice_id', '=', 'sales_invoices.id')->where('sales_invoices.created_by', '=', $id)->latest()->first();
        if($latest)
        {
            return $latest->transaction_id + 1;
        }

        return 1;
    }

    public function grid()
    {
        if(\Auth::user()->can('salesinvoice manage'))
        {
            $invoices = SalesInvoice::where('created_by',creatorId())->where('workspace',getActiveWorkSpace())->get();

            return view('sales::salesinvoice.grid', compact('invoices'));
        }
        else
        {
            return redirect()->back()->with('error', 'permission Denied');
        }
    }
}
