<?php

namespace Modules\DoubleEntry\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceProduct;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Account\Entities\AccountUtility;
use Modules\Account\Entities\ChartOfAccount;
use Modules\Account\Entities\ChartOfAccountSubType;
use Modules\Account\Entities\ChartOfAccountType;
use Modules\DoubleEntry\Entities\JournalItem;

class ReportController extends Controller
{


    public function ledgerReport(Request $request, $account = '')
    {

        if(\Auth::user()->can('report ledger'))
        {
            $accounts =ChartOfAccount::where('created_by', creatorId())->where('workspace' , getActiveWorkSpace())->get()->pluck('name', 'id');
            $accounts->prepend('Select Account', '');


            if(!empty($request->start_date) && !empty($request->end_date))
            {
                $start = $request->start_date;
                $end   = $request->end_date;
            }
            else
            {
                $start = date('Y-m-01');
                $end   = date('Y-m-t');
            }

            if(!empty($request->account))
            {
                $chart_accounts = ChartOfAccount::where('id', $request->account)->get();
            }
            else
            {
                $chart_accounts = ChartOfAccount::where('created_by', creatorId())->where('workspace' , getActiveWorkSpace())->get();
            }


            $balance = 0;
            $debit   = 0;
            $credit  = 0;

            $filter['balance']        = $balance;
            $filter['credit']         = $credit;
            $filter['debit']          = $debit;
            $filter['startDateRange'] = $start;
            $filter['endDateRange']   = $end;


            return view('doubleentry::report.ledger', compact('filter', 'accounts', 'chart_accounts',));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }



    public function balanceSheetPrint(Request $request, $view = '')
    {

        if (\Auth::user()->can('report balance sheet')) {

            if (!empty($request->start_date) && !empty($request->end_date)) {
                $start = $request->start_date;
                $end = $request->end_date;
            } else {
                $start = date('Y-01-01');
                $end = date('Y-m-d', strtotime('+1 day'));
            }

            $types = ChartOfAccountType::where('workspace',getActiveWorkSpace())->where('created_by',creatorId())->whereIn('name', ['Assets', 'Liabilities', 'Equity'])->get();

            $chartAccounts = [];
            foreach ($types as $type) {
                $subTypes = ChartOfAccountSubType::where('type', $type->id)->get();

                $subTypeArray = [];
                foreach ($subTypes as $subType) {
                    $accounts = ChartOfAccount::where('workspace',getActiveWorkSpace())->where('created_by',creatorId())
                        ->where('type', $type->id)
                        ->where('sub_type', $subType->id)
                        ->get();

                    $accountArray = [];
                    $totalAmount = 0;
                    $debitTotal = 0;
                    $creditTotal = 0;
                    $accountSubType = '';
                    $totalBalance = 0;
                    foreach ($accounts as $account) {

                        $getAccount = ChartOfAccount::where('name', $account->name)->where('workspace',getActiveWorkSpace())->where('created_by',creatorId())->first();
                        if ($getAccount) {
                            $Balance = AccountUtility::getAccountBalance($getAccount->id, $start, $end);
                            $totalBalance += $Balance;
                        }

                        if ($Balance != 0) {
                            $data['account_id'] = $account->id;
                            $data['account_code'] = $account->code;
                            $data['account_name'] = $account->name;
                            $data['totalCredit'] = 0;
                            $data['totalDebit'] = 0;
                            $data['netAmount'] = $Balance;
                            $accountArray[] = $data;

                            $creditTotal += $data['totalCredit'];
                            $debitTotal += $data['totalDebit'];
                            $totalAmount += $data['netAmount'];
                        }
                    }
                    $totalAccountArray = [];
                    if ($accountArray != []) {
                        $dataTotal['account_id'] = '';
                        $dataTotal['account_code'] = '';
                        $dataTotal['account_name'] = 'Total ' . $subType->name;
                        $dataTotal['totalCredit'] = $creditTotal;
                        $dataTotal['totalDebit'] = $debitTotal;
                        $dataTotal['netAmount'] = $totalAmount;
                        $accountArrayTotal[] = $dataTotal;

                        $totalAccountArray = array_merge($accountArray, $accountArrayTotal);
                    }

                    if ($totalAccountArray != []) {
                        $subTypeData['subType'] = ($totalAccountArray != []) ? $subType->name : '';
                        $subTypeData['account'] = $totalAccountArray;
                        $subTypeArray[] = ($subTypeData['account'] != [] && $subTypeData['subType'] != []) ? $subTypeData : [];
                    }

                }
                $chartAccounts[$type->name] = $subTypeArray;
            }
            $filter['startDateRange'] = $start;
            $filter['endDateRange'] = $end;

            if ($view == 'horizontal') {
                return view('doubleentry::report.balance_sheet_receipt_horizontal', compact('filter', 'chartAccounts'));
            } else {
                return view('doubleentry::report.balance_sheet_receipt', compact('filter', 'chartAccounts'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }


    public function balanceSheet(Request $request, $view = '')
    {
        if (\Auth::user()->can('report balance sheet')) {
            if (!empty($request->start_date) && !empty($request->end_date)) {
                $start = $request->start_date;
                $end = $request->end_date;
            } else {
                $start = date('Y-01-01');
                $end = date('Y-m-d', strtotime('+1 day'));
            }

            $types = ChartOfAccountType::where('workspace',getActiveWorkSpace())->where('created_by',creatorId())->whereIn('name', ['Assets', 'Liabilities', 'Equity'])->get();
            $chartAccounts = [];
            foreach ($types as $type) {
                $subTypes = ChartOfAccountSubType::where('type', $type->id)->get();

                $subTypeArray = [];
                foreach ($subTypes as $subType) {
                    $accounts = ChartOfAccount::where('workspace',getActiveWorkSpace())->where('created_by',creatorId())
                        ->where('type', $type->id)
                        ->where('sub_type', $subType->id)
                        ->get();

                    $accountArray = [];
                    $totalAmount = 0;
                    $debitTotal = 0;
                    $creditTotal = 0;
                    $accountSubType = '';
                    $totalBalance = 0;
                    foreach ($accounts as $account) {
                        $getAccount = ChartOfAccount::where('name', $account->name)->where('workspace',getActiveWorkSpace())->where('created_by',creatorId())->first();
                        if ($getAccount) {
                            $Balance = AccountUtility::getAccountBalance($getAccount->id, $start, $end);
                            $totalBalance += $Balance;
                        }

                        if ($Balance != 0) {
                            $data['account_id'] = $account->id;
                            $data['account_code'] = $account->code;
                            $data['account_name'] = $account->name;
                            $data['totalCredit'] = 0;
                            $data['totalDebit'] = 0;
                            $data['netAmount'] = $Balance;
                            $accountArray[] = $data;
                            $creditTotal += $data['totalCredit'];
                            $debitTotal += $data['totalDebit'];
                            $totalAmount += $data['netAmount'];
                        }
                    }
                    $totalAccountArray = [];
                    if ($accountArray != []) {
                        $dataTotal['account_id'] = '';
                        $dataTotal['account_code'] = '';
                        $dataTotal['account_name'] = 'Total ' . $subType->name;
                        $dataTotal['totalCredit'] = $creditTotal;
                        $dataTotal['totalDebit'] = $debitTotal;
                        $dataTotal['netAmount'] = $totalAmount;
                        $accountArrayTotal[] = $dataTotal;

                        $totalAccountArray = array_merge($accountArray, $accountArrayTotal);
                    }

                    if ($totalAccountArray != []) {
                        $subTypeData['subType'] = ($totalAccountArray != []) ? $subType->name : '';
                        $subTypeData['account'] = $totalAccountArray;
                        $subTypeArray[] = ($subTypeData['account'] != [] && $subTypeData['subType'] != []) ? $subTypeData : [];
                    }

                }
                $chartAccounts[$type->name] = $subTypeArray;
            }
            $filter['startDateRange'] = $start;
            $filter['endDateRange'] = $end;

            if ($request->view == 'horizontal' || $view == 'horizontal') {
                return view('doubleentry::report.balance_sheet_horizontal', compact('filter', 'chartAccounts'));
            } elseif ($view == '' || $view == 'vertical') {
                return view('doubleentry::report.balance_sheet', compact('filter', 'chartAccounts'));
            } else {
                return redirect()->back();
            }
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
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

    public function yearList()
    {
        $starting_year = date('Y', strtotime('-5 year'));
        $ending_year   = date('Y');

        foreach(range($ending_year, $starting_year) as $year)
        {
            $years[$year] = $year;
        }

        return $years;
    }




    public function profitLoss(Request $request, $view = '')
    {

        if (\Auth::user()->can('report profit loss')) {

            if (!empty($request->start_date) && !empty($request->end_date)) {
                $start = $request->start_date;
                $end = $request->end_date;
            } else {
                $start = date('Y-01-01');
                $end = date('Y-m-d', strtotime('+1 day'));
            }
            $types = ChartOfAccountType::where('workspace',getActiveWorkSpace())->where('created_by', creatorId())->whereIn('name', ['Income', 'Expenses', 'Costs of Goods Sold'])->get();
            $chartAccounts = [];
            $subTypeArray = [];
            foreach ($types as $type) {
                $accounts = ChartOfAccount::where('workspace',getActiveWorkSpace())->where('created_by', creatorId())->where('type', $type->id)->get();
                $totalBalance = 0;
                $creditTotal = 0;
                $debitTotal = 0;
                $totalAmount = 0;
                $accountArray = [];

                foreach ($accounts as $account) {

                    $Balance = AccountUtility::getAccountBalance($account->id, $start, $end);
                    $totalBalance += $Balance;

                    if ($Balance != 0) {
                        $data['account_id'] = $account->id;
                        $data['account_code'] = $account->code;
                        $data['account_name'] = $account->name;
                        $data['totalCredit'] = 0;
                        $data['totalDebit'] = 0;
                        $data['netAmount'] = $Balance;
                        $accountArray[] = $data;

                        $creditTotal += $data['totalCredit'];
                        $debitTotal += $data['totalDebit'];
                        $totalAmount += $data['netAmount'];
                    }
                }

                $totalAccountArray = [];

                if ($accountArray != []) {
                    $dataTotal['account_id'] = '';
                    $dataTotal['account_code'] = '';
                    $dataTotal['account_name'] = 'Total ' . $type->name;
                    $dataTotal['totalCredit'] = $creditTotal;
                    $dataTotal['totalDebit'] = $debitTotal;
                    $dataTotal['netAmount'] = $totalAmount;
                    $accountArray[] = $dataTotal;

                }



                if ($accountArray != []) {
                    $subTypeData['Type'] = ($accountArray != []) ? $type->name : '';
                    $subTypeData['account'] = $accountArray;
                    $subTypeArray[] = ($subTypeData['account'] != []) ? $subTypeData : [];
                }
                $chartAccounts = $subTypeArray;
            }

            $filter['startDateRange'] = $start;
            $filter['endDateRange'] = $end;

            if ($request->view == 'horizontal' || $view == 'horizontal') {
                return view('doubleentry::report.profit_loss_horizontal', compact('filter', 'chartAccounts'));
            } elseif ($view == '' || $view == 'vertical') {
                return view('doubleentry::report.profit_loss', compact('filter', 'chartAccounts'));
            } else {
                return redirect()->back();
            }

        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }



    public function profitLossPrint(Request $request, $view = '')
    {
        if (\Auth::user()->can('report profit loss')) {

            if (!empty($request->start_date) && !empty($request->end_date)) {
                $start = $request->start_date;
                $end = $request->end_date;
            } else {
                $start = date('Y-01-01');
                $end = date('Y-m-d', strtotime('+1 day'));
            }
            $types = ChartOfAccountType::where('workspace',getActiveWorkSpace())->where('created_by', creatorId())->whereIn('name', ['Income', 'Costs of Goods Sold', 'Expenses'])->get();

            $chartAccounts = [];
            $subTypeArray = [];
            foreach ($types as $type) {
                $accounts = ChartOfAccount::where('workspace',getActiveWorkSpace())->where('created_by', creatorId())->where('type', $type->id)->get();

                $totalBalance = 0;
                $creditTotal = 0;
                $debitTotal = 0;
                $totalAmount = 0;
                $accountArray = [];

                foreach ($accounts as $account) {

                    $Balance = AccountUtility::getAccountBalance($account->id, $start, $end);
                    $totalBalance += $Balance;

                    if ($Balance != 0) {
                        $data['account_id'] = $account->id;
                        $data['account_code'] = $account->code;
                        $data['account_name'] = $account->name;
                        $data['totalCredit'] = 0;
                        $data['totalDebit'] = 0;
                        $data['netAmount'] = $Balance;
                        $accountArray[] = $data;

                        $creditTotal += $data['totalCredit'];
                        $debitTotal += $data['totalDebit'];
                        $totalAmount += $data['netAmount'];
                    }
                }

                $totalAccountArray = [];

                if ($accountArray != []) {
                    $dataTotal['account_id'] = '';
                    $dataTotal['account_code'] = '';
                    $dataTotal['account_name'] = 'Total ' . $type->name;
                    $dataTotal['totalCredit'] = $creditTotal;
                    $dataTotal['totalDebit'] = $debitTotal;
                    $dataTotal['netAmount'] = $totalAmount;
                    $accountArray[] = $dataTotal;
                }

                if ($accountArray != []) {
                    $subTypeData['Type'] = ($accountArray != []) ? $type->name : '';
                    $subTypeData['account'] = $accountArray;
                    $subTypeArray[] = ($subTypeData['account'] != []) ? $subTypeData : [];
                }
                $chartAccounts = $subTypeArray;
            }

            $filter['startDateRange'] = $start;
            $filter['endDateRange'] = $end;

            if ($view == 'horizontal') {
                return view('doubleentry::report.profit_loss_receipt_horizontal', compact('filter', 'chartAccounts'));
            } else {
                return view('doubleentry::report.profit_loss_receipt', compact('filter', 'chartAccounts'));
            }

        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }



    public function trialBalanceReport(Request $request)
    {
        if(\Auth::user()->can('report trial balance'))
        {
            if(!empty($request->start_date) && !empty($request->end_date))
            {
                $start = $request->start_date;
                $end   = $request->end_date;
            }
            else
            {
                $start = date('Y-01-01');
                $end   = date('Y-m-d');
            }

            $types         = ChartOfAccountType::where('workspace',getActiveWorkSpace())->where('created_by',creatorId())->get();
            $chartAccounts = [];
            $totalAccounts = [];
            $totalAccount =[];

            foreach($types as $type)
            {


                $total = AccountUtility::trialBalance($type->id,$start,$end);
                $name = $type->name;
                if (isset($totalAccount[$name])) {
                    $totalAccount[$name]["totalCredit"] += $total["totalCredit"];
                    $totalAccount[$name]["totalDebit"] += $total["totalDebit"];
                } else {
                    $totalAccount[$name] = $total;
                }
            }

            foreach ($totalAccount as $category => $entries) {
                foreach ($entries as $entry) {
                    $name = $entry['name'];

                    if (!isset($totalAccounts[$category][$name])) {
                        $totalAccounts[$category][$name] = [
                            'id'=>$entry['id'],
                            'code' => $entry['code'],
                            'name' => $name,
                            'totalDebit' => 0,
                            'totalCredit' => 0,
                        ];
                    }
                    $totalAccounts[$category][$name]['totalDebit'] += $entry['totalDebit'];
                    $totalAccounts[$category][$name]['totalCredit'] += $entry['totalCredit'];
                }
            }

            $filter['startDateRange'] = $start;
            $filter['endDateRange']   = $end;

            return view('doubleentry::report.trial_balance', compact('filter', 'totalAccounts'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function trialBalancePrint(Request $request)
    {
        if (\Auth::user()->can('report trial balance')) {
            if (!empty($request->start_date) && !empty($request->end_date)) {
                $start = $request->start_date;
                $end = $request->end_date;
            } else {
                $start = date('Y-01-01');
                $end = date('Y-m-d', strtotime('+1 day'));
            }

            $types = ChartOfAccountType::where('workspace',getActiveWorkSpace())->where('created_by', creatorId())->get();
            $chartAccounts = [];

            $totalAccounts = [];

            foreach ($types as $type) {
                $total = AccountUtility::trialBalance($type->id, $start, $end);
                $name = $type->name;
                if (isset($totalAccount[$name])) {
                    $totalAccount[$name]["totalCredit"] += $total["totalCredit"];
                    $totalAccount[$name]["totalDebit"] += $total["totalDebit"];
                } else {
                    $totalAccount[$name] = $total;
                }

            }

            foreach ($totalAccount as $category => $entries) {
                foreach ($entries as $entry) {
                    $name = $entry['name'];

                    if (!isset($totalAccounts[$category][$name])) {
                        $totalAccounts[$category][$name] = [
                            'id' => $entry['id'],
                            'code' => $entry['code'],
                            'name' => $name,
                            'totalDebit' => 0,
                            'totalCredit' => 0,
                        ];
                    }
                    $totalAccounts[$category][$name]['totalDebit'] += $entry['totalDebit'];
                    $totalAccounts[$category][$name]['totalCredit'] += $entry['totalCredit'];
                }
            }

            $filter['startDateRange'] = $start;
            $filter['endDateRange'] = $end;

            return view('doubleentry::report.trial_balance_receipt', compact('filter', 'totalAccounts'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function salesReport(Request $request)
    {
        if (!empty($request->start_date) && !empty($request->end_date)) {
            $start = $request->start_date;
            $end = $request->end_date;
        } else {
            $start = date('Y-01-01');
            $end = date('Y-m-d', strtotime('+1 day'));
        }

        $invoiceItems = InvoiceProduct::select('product_services.name', \DB::raw('sum(invoice_products.quantity) as quantity'), \DB::raw('sum(invoice_products.price) as price'), \DB::raw('sum(invoice_products.price)/sum(invoice_products.quantity) as avg_price'));
        $invoiceItems->leftjoin('product_services', 'product_services.id', 'invoice_products.product_id');
        $invoiceItems->where('product_services.created_by', creatorId());
        $invoiceItems->where('product_services.workspace_id', getActiveWorkSpace());
        $invoiceItems->where('invoice_products.created_at', '>=', $start);
        $invoiceItems->where('invoice_products.created_at', '<=', $end);
        $invoiceItems->groupBy('invoice_products.product_id');
        $invoiceItems = $invoiceItems->get()->toArray();

//        dd($invoiceItems);

        $invoiceCustomers = Invoice::select('customers.name',
            \DB::raw('count(invoices.customer_id) as invoice_count'),
            \DB::raw('sum(invoice_products.price) as price'),
            \DB::raw('sum(invoice_products.price * (taxes.rate / 100 )) as total_tax')
        );

        $invoiceCustomers->leftJoin('customers', 'customers.id', 'invoices.customer_id');
        $invoiceCustomers->leftJoin('invoice_products', 'invoice_products.invoice_id', 'invoices.id');
        $invoiceCustomers->leftJoin('taxes', \DB::raw('FIND_IN_SET(taxes.id, invoice_products.tax)'), '>', \DB::raw('0'));
        $invoiceCustomers->where('invoices.created_by', creatorId());
        $invoiceCustomers->where('invoices.workspace', getActiveWorkSpace());
        $invoiceCustomers->where('invoices.created_at', '>=', $start);
        $invoiceCustomers->where('invoices.created_at', '<=', $end);
        $invoiceCustomers->groupBy('invoices.customer_id');
        $invoiceCustomers = $invoiceCustomers->get()->toArray();

        $filter['startDateRange'] = $start;
        $filter['endDateRange'] = $end;

        return view('doubleentry::report.sales_report', compact('filter', 'invoiceItems', 'invoiceCustomers'));
    }

    public function salesReportPrint(Request $request)
    {
        if (!empty($request->start_date) && !empty($request->end_date)) {
            $start = $request->start_date;
            $end = $request->end_date;
        } else {
            $start = date('Y-01-01');
            $end = date('Y-m-d', strtotime('+1 day'));
        }

        $invoiceItems = InvoiceProduct::select('product_services.name', \DB::raw('sum(invoice_products.quantity) as quantity'), \DB::raw('sum(invoice_products.price) as price'), \DB::raw('sum(invoice_products.price)/sum(invoice_products.quantity) as avg_price'));
        $invoiceItems->leftjoin('product_services', 'product_services.id', 'invoice_products.product_id');
        $invoiceItems->where('product_services.created_by', creatorId());
        $invoiceItems->where('product_services.workspace_id', getActiveWorkSpace());
        $invoiceItems->where('invoice_products.created_at', '>=', $start);
        $invoiceItems->where('invoice_products.created_at', '<=', $end);
        $invoiceItems->groupBy('invoice_products.product_id');
        $invoiceItems = $invoiceItems->get()->toArray();

        $invoiceCustomers = Invoice::select('customers.name',
            \DB::raw('count(invoices.customer_id) as invoice_count'),
            \DB::raw('sum(invoice_products.price) as price'),
            \DB::raw('sum(invoice_products.price * (taxes.rate / 100 )) as total_tax')
        );

        $invoiceCustomers->leftJoin('customers', 'customers.id', 'invoices.customer_id');
        $invoiceCustomers->leftJoin('invoice_products', 'invoice_products.invoice_id', 'invoices.id');
        $invoiceCustomers->leftJoin('taxes', \DB::raw('FIND_IN_SET(taxes.id, invoice_products.tax)'), '>', \DB::raw('0'));
        $invoiceCustomers->where('invoices.created_by', creatorId());
        $invoiceCustomers->where('invoices.workspace', getActiveWorkSpace());
        $invoiceCustomers->where('invoices.created_at', '>=', $start);
        $invoiceCustomers->where('invoices.created_at', '<=', $end);
        $invoiceCustomers->groupBy('invoices.customer_id');
        $invoiceCustomers = $invoiceCustomers->get()->toArray();

        $filter['startDateRange'] = $start;
        $filter['endDateRange'] = $end;

        $reportName = $request->report;

        return view('doubleentry::report.sales_report_receipt', compact('filter', 'invoiceItems', 'invoiceCustomers', 'reportName'));
    }












}
