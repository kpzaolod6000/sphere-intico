<?php

namespace Modules\Sales\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Sales\Entities\SalesAccount;
use Modules\Sales\Entities\SalesInvoice;
use Modules\Sales\Entities\SalesOrder;
use Modules\Sales\Entities\Quote;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('sales::index');
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

    public function invoiceanalytic(Request $request)
    {
        $report['account'] = __('All');
        $report['status']  = __('All');
        $account           = SalesAccount::where('created_by', '=', creatorId())->where('workspace', '=', getActiveWorkSpace())->get()->pluck('name', 'id');
        $account->prepend('Select Account', '');
        $status = SalesInvoice::$status;

        $data = [];

        if(!empty($request->start_month) && !empty($request->end_month))
        {
            $start = strtotime($request->start_month);
            $end   = strtotime($request->end_month);
        }
        else
        {
            $start = strtotime(date('Y-01'));
            $end   = strtotime(date('Y-12'));
        }
        $invoice = SalesInvoice::selectRaw('sales_invoices.*,MONTH(date_quoted) as month,YEAR(date_quoted) as year')->orderBy('id');

        $invoice->where('date_quoted', '>=', date('Y-m-01', $start))->where('date_quoted', '<=', date('Y-m-t', $end));
        if(!empty($request->account))
        {
            $invoice->where('account', $request->account);
        }
        if(!empty($request->status))
        {
            $invoice->where('status', $request->status);
        }

        $invoice->where('created_by', creatorId())->where('workspace', '=', getActiveWorkSpace());
        $invoice = $invoice->get();

        $totalInvoice      = 0;
        $totalDueInvoice   = 0;
        $invoiceTotalArray = [];
        foreach($invoice as $invoic)
        {
            $totalInvoice                        += $invoic->getTotal();
            $invoiceTotalArray[$invoic->month][] = $invoic->getTotal();
        }

        for($i = 1; $i <= 12; $i++)
        {
            $invoiceTotal[] = array_key_exists($i, $invoiceTotalArray) ? array_sum($invoiceTotalArray[$i]) : 0;
        }
        $monthList = $month = $this->yearMonth();

        $report['startDateRange'] = date('M-Y', $start);
        $report['endDateRange']   = date('M-Y', $end);

        return view('sales::report.invoiceanalytic', compact('monthList', 'data', 'report', 'invoice', 'account', 'status', 'totalInvoice', 'invoiceTotal'));
    }

    public function yearMonth()
    {

        $month[] = __('January');
        $month[] = __('February');
        $month[] = __('March');
        $month[] = __('April');
        $month[] = __('May');
        $month[] = __('June');
        $month[] = __('July');
        $month[] = __('August');
        $month[] = __('September');
        $month[] = __('October');
        $month[] = __('November');
        $month[] = __('December');

        return $month;
    }

    public function salesorderanalytic(Request $request)
    {
        $status = SalesOrder::$status;

        $data = [];

        if(!empty($request->start_month) && !empty($request->end_month))
        {
            $start = strtotime($request->start_month);
            $end   = strtotime($request->end_month);
        }
        else
        {
            $start = strtotime(date('Y-01'));
            $end   = strtotime(date('Y-12'));
        }
        $salesorder = SalesOrder::selectRaw('sales_orders.*,MONTH(date_quoted) as month,YEAR(date_quoted) as year')->orderBy('id');

        $salesorder->where('date_quoted', '>=', date('Y-m-01', $start))->where('date_quoted', '<=', date('Y-m-t', $end));

        if(!empty($request->status))
        {
            $salesorder->where('status', $request->status);
        }

        $salesorder->where('created_by', creatorId())->where('workspace', '=', getActiveWorkSpace());
        $salesorder = $salesorder->get();

        $totalSalesorder      = 0;
        $salesorderTotalArray = [];
        foreach($salesorder as $salesord)
        {
            $totalSalesorder                          += $salesord->getTotal();
            $salesorderTotalArray[$salesord->month][] = $salesord->getTotal();
        }

        for($i = 1; $i <= 12; $i++)
        {
            $salesorderTotal[] = array_key_exists($i, $salesorderTotalArray) ? array_sum($salesorderTotalArray[$i]) : 0;
        }
        $monthList = $month = $this->yearMonth();

        $report['startDateRange'] = date('M-Y', $start);
        $report['endDateRange']   = date('M-Y', $end);

        return view('sales::report.salesorderanalytic', compact('monthList', 'data', 'report', 'salesorder', 'status', 'totalSalesorder', 'salesorderTotal'));

    }

    public function quoteanalytic(Request $request)
    {
        $status = Quote::$status;

        $data = [];

        if(!empty($request->start_month) && !empty($request->end_month))
        {
            $start = strtotime($request->start_month);
            $end   = strtotime($request->end_month);
        }
        else
        {
            $start = strtotime(date('Y-01'));
            $end   = strtotime(date('Y-12'));
        }
        $quote = Quote::selectRaw('quotes.*,MONTH(date_quoted) as month,YEAR(date_quoted) as year')->orderBy('id');

        $quote->where('date_quoted', '>=', date('Y-m-01', $start))->where('date_quoted', '<=', date('Y-m-t', $end));
        if(!empty($request->status))
        {
            $quote->where('status', $request->status);
        }

        $quote->where('created_by', creatorId())->where('workspace', '=', getActiveWorkSpace());
        $quote = $quote->get();
        $totalQuote = 0;
        $quoteTotalArray = [];
        foreach($quote as $quot)
        {
            $totalQuote                     += $quot->getTotal();
            $quoteTotalArray[$quot->month][] = $quot->getTotal();
        }

        for($i = 1; $i <= 12; $i++)
        {
            $quoteTotal[] = array_key_exists($i, $quoteTotalArray) ? array_sum($quoteTotalArray[$i]) : 0;
        }
        $monthList = $month = $this->yearMonth();

        $report['startDateRange'] = date('M-Y', $start);
        $report['endDateRange']   = date('M-Y', $end);

        return view('sales::report.quoteanalytic', compact('monthList', 'data', 'report','quote', 'totalQuote', 'status', 'quoteTotal'));
    }
}
