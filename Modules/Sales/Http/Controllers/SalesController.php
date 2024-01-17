<?php

namespace Modules\Sales\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Rawilk\Settings\Support\Context;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Carbon\Carbon;
use Modules\Sales\Entities\SalesAccount;
use Modules\Sales\Entities\Contact;
use Modules\Sales\Entities\SalesOrder;
use Modules\Sales\Entities\SalesInvoice;
use Modules\Sales\Entities\Quote;
use Modules\Sales\Entities\Opportunities;
use Modules\Sales\Entities\Meeting;
use Modules\Sales\Entities\Call;
use Illuminate\Support\Facades\Auth;

class SalesController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function __construct()
    {
        if(module_is_active('GoogleAuthentication'))
        {
            $this->middleware('2fa');
        }
    }
    public function index()
    {
        if (Auth::user()->can('sales dashboard manage'))
        {
            $data['totalUser']          = User::where('created_by', creatorId())->where('workspace_id', '=', getActiveWorkSpace())->count();
            $data['totalAccount']       = SalesAccount::where('created_by', creatorId())->where('workspace', '=', getActiveWorkSpace())->count();
            $data['totalContact']       = Contact::where('created_by', creatorId())->where('workspace', '=', getActiveWorkSpace())->count();
            $data['totalSalesorder']    = $totalSalesOrder = SalesOrder::where('created_by', creatorId())->where('workspace', '=', getActiveWorkSpace())->count();
            $data['totalInvoice']       = $totalInvoice = SalesInvoice::where('created_by', creatorId())->where('workspace', '=', getActiveWorkSpace())->count();
            $data['totalQuote']         = $totalQuote = Quote::where('created_by', creatorId())->where('workspace', '=', getActiveWorkSpace())->count();
            $data['totalOpportunities'] = Opportunities::where('created_by', creatorId())->where('workspace', '=', getActiveWorkSpace())->count();
            $data['invoiceColor']       = SalesInvoice::$statuesColor;


            $statuss  = SalesInvoice::$status;
            $invoices = [];
            foreach($statuss as $id => $status)
            {
                $invoice                   = $total = SalesInvoice::where('status', $id)->where('created_by', creatorId())->where('workspace', '=', getActiveWorkSpace())->count();
                $percentage                = ($totalInvoice != 0) ? ($total * 100) / $totalInvoice : '0';
                $invoicedata['percentage'] = number_format($percentage, 2);
                $invoicedata['data']       = $invoice;
                $invoicedata['status']     = $status;
                $invoices[]                = $invoicedata;

            }

            $data['invoice'] = $invoices;


            $statuss = Quote::$status;
            $quotes  = [];
            foreach($statuss as $id => $status)
            {
                $quote = $total = Quote::where('status', $id)->where('created_by',creatorId())->where('workspace', '=', getActiveWorkSpace())->count();

                $percentage              = ($totalQuote != 0) ? ($total * 100) / $totalQuote : '0';
                $quotedata['percentage'] = number_format($percentage, 2);
                $quotedata['data']       = $quote;
                $quotedata['status']     = $status;
                $quotes[]                = $quotedata;
            }
            $data['quote'] = $quotes;


            $statuss     = SalesOrder::$status;
            $salesOrders = [];
            foreach($statuss as $id => $status)
            {
                $salesorder                   = SalesOrder::where('status', $id)->where('created_by',creatorId())->where('workspace', '=', getActiveWorkSpace())->count();
                $percentage                   = ($totalSalesOrder != 0) ? ($total * 100) / $totalSalesOrder : '0';
                $salesorderdata['percentage'] = number_format($percentage, 2);
                $salesorderdata['data']       = $salesorder;
                $salesorderdata['status']     = $status;
                $salesOrders[]                = $salesorderdata;
            }
            $data['salesOrder'] = $salesOrders;

            $data['lineChartData'] = $this->getIncExpLineChartDate();

            $data['topMeeting']         = Meeting::where('created_by',creatorId())->where('workspace', '=', getActiveWorkSpace())->where('start_date', '>', date('Y-m-d'))->limit(5)->get();
            $data['thisMonthCall']      = Call::whereBetween(
                'start_date', [
                                \Carbon\Carbon::now()->startOfMonth(),
                                \Carbon\Carbon::now()->endOfMonth(),
                            ]
            )->where('created_by', creatorId())->limit(5)->get();

            return view('sales::dashboard.index', compact('data'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function getIncExpLineChartDate()
    {
        $usr           = Auth::user();
        $m             = date("m");
        $de            = date("d");
        $y             = date("Y");
        $format        = 'Y-m-d';
        $arrDate       = [];
        $arrDateFormat = [];

        for($i = 0; $i <= 15 - 1; $i++)
        {
            $date = date($format, mktime(0, 0, 0, $m, ($de - $i), $y));

            $arrDay[]        = date('D', mktime(0, 0, 0, $m, ($de - $i), $y));
            $arrDate[]       = $date;
            $arrDateFormat[] = date("d", strtotime($date)) .'-'.__(date("M", strtotime($date)));
        }
        $data['day'] = $arrDateFormat;

        for($i = 0; $i < count($arrDate); $i++)
        {
            $daysQuotes = Quote:: select('*')->where('created_by', creatorId())->where('workspace', '=', getActiveWorkSpace())->whereRAW('date_quoted = ?', $arrDate[$i])->get();
            $quoteArray = array();
            foreach($daysQuotes as $daysQuote)
            {
                $quoteArray[] = $daysQuote->getTotal();
            }
            $quoteamount = number_format(!empty($quoteArray) ? array_sum($quoteArray) : 0, 2);
            $quateData[] = str_replace(',', '', $quoteamount);


            $daysInvoices = SalesInvoice:: select('*')->where('created_by', creatorId())->where('workspace', '=', getActiveWorkSpace())->whereRAW('date_quoted = ?', $arrDate[$i])->get();

            $invoiceArray = array();
            foreach($daysInvoices as $daysInvoice)
            {
                $invoiceArray[] = $daysInvoice->getTotal();
            }
            $invoiceamount = number_format(!empty($invoiceArray) ? array_sum($invoiceArray) : 0, 2);
            $invoiceData[] = str_replace(',', '', $invoiceamount);

            $daysSalesOrders = SalesOrder:: select('*')->where('created_by', creatorId())->where('workspace', '=', getActiveWorkSpace())->whereRAW('date_quoted = ?', $arrDate[$i])->get();

            $salesOrderArray = array();
            foreach($daysSalesOrders as $daysSalesOrder)
            {
                $salesOrderArray[] = $daysSalesOrder->getTotal();
            }
            $salesorderamount = number_format(!empty($salesOrderArray) ? array_sum($salesOrderArray) : 0, 2);
            $salesOrderData[] = str_replace(',', '', $salesorderamount);
        }

        $data['invoiceAmount']    = $invoiceData;
        $data['quoteAmount']      = $quateData;
        $data['salesorderAmount'] = $salesOrderData;

        return $data;
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('sales::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('sales::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('sales::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }

    public function setting(Request $request)
    {
        $validator = Validator::make($request->all(),
        [
            'quote_prefix' => 'required',
            'salesorder_prefix' => 'required',
            'salesinvoice_prefix' => 'required',
            'quote_footer_title' => 'required',
            'quote_footer_notes' => 'required',
            'quote_shipping_display' => 'required',
        ]);
        if($validator->fails()){
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first());
        }
        else
        {
            $userContext = new Context(['user_id' => Auth::user()->id,'workspace_id'=>getActiveWorkSpace()]);
            \Settings::context($userContext)->set('quote_prefix', $request->quote_prefix);
            \Settings::context($userContext)->set('quote_footer_title', $request->quote_footer_title);
            \Settings::context($userContext)->set('quote_footer_notes', $request->quote_footer_notes);
            \Settings::context($userContext)->set('quote_shipping_display', $request->quote_shipping_display);
            \Settings::context($userContext)->set('salesorder_prefix', $request->salesorder_prefix);
            \Settings::context($userContext)->set('salesorder_footer_title', $request->salesorder_footer_title);
            \Settings::context($userContext)->set('salesorder_footer_notes', $request->salesorder_footer_notes);
            \Settings::context($userContext)->set('salesorder_shipping_display', $request->salesorder_shipping_display);
            \Settings::context($userContext)->set('salesinvoice_prefix', $request->salesinvoice_prefix);
            \Settings::context($userContext)->set('salesinvoice_footer_title', $request->salesinvoice_footer_title);
            \Settings::context($userContext)->set('salesinvoice_footer_notes', $request->salesinvoice_footer_notes);
            \Settings::context($userContext)->set('salesinvoice_shipping_display', $request->salesinvoice_shipping_display);
            return redirect()->back()->with('success','Quote / Sales Order setting save sucessfully.');
        }
    }
}
