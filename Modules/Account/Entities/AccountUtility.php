<?php

namespace Modules\Account\Entities;

use App\Models\InvoicePayment;
use App\Models\InvoiceProduct;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use App\Models\WorkSpace;
use Rawilk\Settings\Support\Context;

class AccountUtility extends Model
{
    use HasFactory;

    public static function countCustomers()
    {
        return Customer::where('workspace', '=', getActiveWorkSpace())->count();
    }
    public static function countVendors()
    {
        return Vender::where('workspace', '=', getActiveWorkSpace())->count();
    }
    public static function countBills()
    {
        return Bill::where('workspace', '=', getActiveWorkSpace())->count();
    }
    public static function todayIncome()
    {
        $revenue      = Revenue::where('workspace', '=', getActiveWorkSpace())->whereRaw('Date(date) = CURDATE()')->sum('amount');
        $invoices     = \App\Models\Invoice:: select('*')->where('workspace', getActiveWorkSpace())->where('invoice_module','account')->whereRAW('Date(send_date) = CURDATE()')->get();
        $invoiceArray = array();
        foreach($invoices as $invoice)
        {
            $invoiceArray[] = $invoice->getTotal();
        }
        $totalIncome = (!empty($revenue) ? $revenue : 0) + (!empty($invoiceArray) ? array_sum($invoiceArray) : 0);

        return $totalIncome;
    }

    public static function todayExpense()
    {
        $payment = Payment::where('workspace', '=', getActiveWorkSpace())->whereRaw('Date(date) = CURDATE()')->sum('amount');

        $bills = Bill:: select('*')->where('workspace', getActiveWorkSpace())->whereRAW('Date(send_date) = CURDATE()')->get();

        $billArray = array();
        foreach($bills as $bill)
        {
            $billArray[] = $bill->getTotal();
        }

        $totalExpense = (!empty($payment) ? $payment : 0) + (!empty($billArray) ? array_sum($billArray) : 0);

        return $totalExpense;
    }

    public static function incomeCurrentMonth()
    {
        $currentMonth = date('m');
        $revenue      = Revenue::where('workspace', '=', getActiveWorkSpace())->whereRaw('MONTH(date) = ?', [$currentMonth])->sum('amount');

        $invoices = \App\Models\Invoice:: select('*')->where('workspace', getActiveWorkSpace())->where('invoice_module','account')->whereRAW('MONTH(send_date) = ?', [$currentMonth])->get();

        $invoiceArray = array();
        foreach($invoices as $invoice)
        {
            $invoiceArray[] = $invoice->getTotal();
        }
        $totalIncome = (!empty($revenue) ? $revenue : 0) + (!empty($invoiceArray) ? array_sum($invoiceArray) : 0);

        return $totalIncome;

    }

    public static function expenseCurrentMonth()
    {
        $currentMonth = date('m');

        $payment = Payment::where('workspace', '=', getActiveWorkSpace())->whereRaw('MONTH(date) = ?', [$currentMonth])->sum('amount');

        $bills     = Bill:: select('*')->where('workspace', getActiveWorkSpace())->whereRAW('MONTH(send_date) = ?', [$currentMonth])->get();
        $billArray = array();
        foreach($bills as $bill)
        {
            $billArray[] = $bill->getTotal();
        }

        $totalExpense = (!empty($payment) ? $payment : 0) + (!empty($billArray) ? array_sum($billArray) : 0);

        return $totalExpense;
    }


    public static function tax($taxes)
    {
        if(module_is_active('ProductService'))
        {
            $taxArr = explode(',', $taxes);
            $taxes  = [];
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
    public static function taxRate($taxRate, $price, $quantity,$discount= 0)
    {
        return (($price*$quantity) - $discount) * ($taxRate /100);
    }
    public static function totalTaxRate($taxes)
    {
        if(module_is_active('ProductService'))
        {
            $taxArr  = explode(',', $taxes);
            $taxRate = 0;
            foreach($taxArr as $tax)
            {
                $tax     = \Modules\ProductService\Entities\Tax::find($tax);
                $taxRate += !empty($tax->rate) ? $tax->rate : 0;
            }
            return $taxRate;
        }
        else
        {
            return 0;
        }
    }

    //start for customer and vendor balance update
    public static function userBalance($users, $id, $amount, $type)
    {
        if($users == 'customer')
        {
            $user = Customer::find($id);
        }
        else
        {
            $user = Vender::find($id);
        }

        if(!empty($user))
        {
            if($type == 'credit')
            {
                $oldBalance    = $user->balance;
                $userBalance = $oldBalance + $amount;
                $user->balance = $userBalance;
                $user->save();
            }
            elseif($type == 'debit')
            {
                $oldBalance    = $user->balance;
                $userBalance = $oldBalance - $amount;
                $user->balance = $userBalance;
                $user->save();
            }
        }
    }

    public static function updateUserBalance($users, $id, $amount, $type)
    {
        if($users == 'customer')
        {
            $user = Customer::find($id);
        }
        else
        {
            $user = Vender::find($id);
        }
        if(!empty($user))
        {
            if($type == 'credit')
            {
                $oldBalance    = $user->balance;
                $userBalance = $oldBalance - $amount;
                $user->balance = $userBalance;
                $user->save();
            }
            elseif($type == 'debit')
            {
                $oldBalance    = $user->balance;
                $userBalance = $oldBalance + $amount;
                $user->balance = $userBalance;
                $user->save();
            }
        }
    }
    //end for customer and vendor balance update

    public static function templateData()
    {
        $arr              = [];
        $arr['colors']    = [
            '003580',
            '666666',
            '6676ef',
            'f50102',
            'f9b034',
            'fbdd03',
            'c1d82f',
            '37a4e4',
            '8a7966',
            '6a737b',
            '050f2c',
            '0e3666',
            '3baeff',
            '3368e6',
            'b84592',
            'f64f81',
            'f66c5f',
            'fac168',
            '46de98',
            '40c7d0',
            'be0028',
            '2f9f45',
            '371676',
            '52325d',
            '511378',
            '0f3866',
            '48c0b6',
            '297cc0',
            'ffffff',
            '000',
        ];
        $arr['templates'] = [
            "template1" => "New York",
            "template2" => "Toronto",
            "template3" => "Rio",
            "template4" => "London",
            "template5" => "Istanbul",
            "template6" => "Mumbai",
            "template7" => "Hong Kong",
            "template8" => "Tokyo",
            "template9" => "Sydney",
            "template10" => "Paris",
        ];
        return $arr;
    }
    // get font-color code accourding to bg-color
    public static function hex2rgb($hex)
    {
        $hex = str_replace("#", "", $hex);

        if(strlen($hex) == 3)
        {
            $r = hexdec(substr($hex, 0, 1) . substr($hex, 0, 1));
            $g = hexdec(substr($hex, 1, 1) . substr($hex, 1, 1));
            $b = hexdec(substr($hex, 2, 1) . substr($hex, 2, 1));
        }
        else
        {
            $r = hexdec(substr($hex, 0, 2));
            $g = hexdec(substr($hex, 2, 2));
            $b = hexdec(substr($hex, 4, 2));
        }
        $rgb = array(
            $r,
            $g,
            $b,
        );

        //return implode(",", $rgb); // returns the rgb values separated by commas
        return $rgb; // returns an array with the rgb values
    }
    public static function getFontColor($color_code)
    {
        $rgb = self::hex2rgb($color_code);
        $R   = $G = $B = $C = $L = $color = '';

        $R = (floor($rgb[0]));
        $G = (floor($rgb[1]));
        $B = (floor($rgb[2]));

        $C = [
            $R / 255,
            $G / 255,
            $B / 255,
        ];

        for($i = 0; $i < count($C); ++$i)
        {
            if($C[$i] <= 0.03928)
            {
                $C[$i] = $C[$i] / 12.92;
            }
            else
            {
                $C[$i] = pow(($C[$i] + 0.055) / 1.055, 2.4);
            }
        }

        $L = 0.2126 * $C[0] + 0.7152 * $C[1] + 0.0722 * $C[2];

        if($L > 0.179)
        {
            $color = 'black';
        }
        else
        {
            $color = 'white';
        }

        return $color;
    }
    public static function addProductStock($product_id, $quantity, $type, $description,$type_id)
    {
        $stocks                = new StockReport();
        $stocks->product_id    = $product_id;
        $stocks->quantity	    = $quantity;
        $stocks->type          = $type;
        $stocks->type_id       = $type_id;
        $stocks->description   = $description;
        $stocks->workspace     = getActiveWorkSpace();
        $stocks->created_by    = \Auth::user()->id;
        $stocks->save();
    }

    public static function incomeCategoryRevenueAmount($id = null)
    {
        if($id != null)
        {
            $year    = date('Y');
            $revenue = Revenue::where('category_id', $id)->where('workspace', getActiveWorkSpace())->whereRAW('YEAR(date) =?', [$year])->sum('amount');

            $invoices     = \App\Models\Invoice::where('category_id',$id)->where('workspace', getActiveWorkSpace())->where('invoice_module','account')->whereRAW('YEAR(send_date) =?', [$year])->get();
            $invoiceArray = array();
            foreach($invoices as $invoice)
            {
                $invoiceArray[] = $invoice->getTotal();
            }
            $totalIncome = (!empty($revenue) ? $revenue : 0) + (!empty($invoiceArray) ? array_sum($invoiceArray) : 0);
            return $totalIncome;
        }
        else
        {
            return 0;
        }


    }
    public static function expenseCategoryAmount($id = null)
    {
        if($id != null)
        {
            $year    = date('Y');
            $payment = Payment::where('category_id', $id)->where('workspace', getActiveWorkSpace())->whereRAW('YEAR(date) =?', [$year])->sum('amount');

            $bills     = Bill::where('category_id', $id)->where('workspace', getActiveWorkSpace())->whereRAW('YEAR(send_date) =?', [$year])->get();
            $billArray = array();
            foreach($bills as $bill)
            {
                $billArray[] = $bill->getTotal();
            }

            $totalExpense = (!empty($payment) ? $payment : 0) + (!empty($billArray) ? array_sum($billArray) : 0);

            return $totalExpense;
        }
        else
        {
            return 0;
        }
    }
    public static function getincExpBarChartData()
    {
        $month[]          = __('January');
        $month[]          = __('February');
        $month[]          = __('March');
        $month[]          = __('April');
        $month[]          = __('May');
        $month[]          = __('June');
        $month[]          = __('July');
        $month[]          = __('August');
        $month[]          = __('September');
        $month[]          = __('October');
        $month[]          = __('November');
        $month[]          = __('December');
        $dataArr['month'] = $month;

        for($i = 1; $i <= 12; $i++)
        {
            $monthlyIncome = Revenue::selectRaw('sum(amount) amount')->where('workspace', '=', getActiveWorkSpace())->whereRaw('year(`date`) = ?', array(date('Y')))->whereRaw('month(`date`) = ?', $i)->first();
            $invoices      = \App\Models\Invoice::select('*')->where('workspace', getActiveWorkSpace())->whereRaw('year(`send_date`) = ?', array(date('Y')))->where('invoice_module','account')->whereRaw('month(`send_date`) = ?', $i)->get();

            $invoiceArray = array();
            foreach($invoices as $invoice)
            {
                $invoiceArray[] = $invoice->getTotal();
            }
            $totalIncome = (!empty($monthlyIncome) ? $monthlyIncome->amount : 0) + (!empty($invoiceArray) ? array_sum($invoiceArray) : 0);

            $incomeArr[] = !empty($totalIncome) ? number_format($totalIncome, 2) : 0;

            $monthlyExpense = Payment::selectRaw('sum(amount) amount')->where('workspace', '=', getActiveWorkSpace())->whereRaw('year(`date`) = ?', array(date('Y')))->whereRaw('month(`date`) = ?', $i)->first();
            $bills          = Bill::select('*')->where('workspace', getActiveWorkSpace())->whereRaw('year(`send_date`) = ?', array(date('Y')))->whereRaw('month(`send_date`) = ?', $i)->get();
            $billArray      = array();
            foreach($bills as $bill)
            {
                $billArray[] = $bill->getTotal();
            }
            $totalExpense = (!empty($monthlyExpense) ? $monthlyExpense->amount : 0) + (!empty($billArray) ? array_sum($billArray) : 0);

            $expenseArr[] = !empty($totalExpense) ? number_format($totalExpense, 2) : 0;
        }

        $dataArr['income']  = $incomeArr;
        $dataArr['expense'] = $expenseArr;
        return $dataArr;
    }
    public static function getIncExpLineChartDate()
    {
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
            $arrDateFormat[] = date("d-M", strtotime($date));
        }
        $dataArr['day'] = $arrDateFormat;

        for($i = 0; $i < count($arrDate); $i++)
        {
            $dayIncome = Revenue::selectRaw('sum(amount) amount')->where('workspace', getActiveWorkSpace())->whereRaw('date = ?', $arrDate[$i])->first();

            $invoices     = \App\Models\Invoice:: select('*')->where('workspace', getActiveWorkSpace())->where('invoice_module','account')->whereRAW('send_date = ?', $arrDate[$i])->get();
            $invoiceArray = array();
            foreach($invoices as $invoice)
            {
                $invoiceArray[] = $invoice->getTotal();
            }

            $incomeAmount = (!empty($dayIncome->amount) ? $dayIncome->amount : 0) + (!empty($invoiceArray) ? array_sum($invoiceArray) : 0);
            $incomeArr[]  = str_replace(",", "", number_format($incomeAmount, 2));

            $dayExpense = Payment::selectRaw('sum(amount) amount')->where('workspace', getActiveWorkSpace())->whereRaw('date = ?', $arrDate[$i])->first();

            $bills     = Bill:: select('*')->where('workspace', getActiveWorkSpace())->whereRAW('send_date = ?', $arrDate[$i])->get();
            $billArray = array();
            foreach($bills as $bill)
            {
                $billArray[] = $bill->getTotal();
            }
            $expenseAmount = (!empty($dayExpense->amount) ? $dayExpense->amount : 0) + (!empty($billArray) ? array_sum($billArray) : 0);
            $expenseArr[]  = str_replace(",", "", number_format($expenseAmount, 2));
        }

        $dataArr['income']  = $incomeArr;
        $dataArr['expense'] = $expenseArr;

        return $dataArr;
    }

    public static function GivePermissionToRoles($role_id = null,$rolename = null)
    {
        $client_permissions=[
            'creditnote manage',
            'revenue manage',
            'account manage',
            'sidebar income manage',

        ];


        $vendor_permissions=[
            'account manage',
            'vendor show',
            'bill show',
            'user profile manage',
            'vendor manage',
            'sidebar expanse manage',
            'bill manage',
            'bill payment manage',
            'workspace manage',


        ];

        if($role_id == Null)
        {
            // client
            $roles_c = Role::where('name','client')->get();
            foreach($roles_c as $role)
            {
                foreach($client_permissions as $permission_c){
                    $permission = Permission::where('name',$permission_c)->first();
                    $role->givePermissionTo($permission);
                }
            }

            // vendor
            $roles_v = Role::where('name','vendor')->get();

            foreach($roles_v as $role)
            {
                foreach($vendor_permissions as $permission_v){
                    $permission = Permission::where('name',$permission_v)->first();
                    $role->givePermissionTo($permission);
                }
            }

        }
        else
        {
            if($rolename == 'client')
            {
                $roles_c = Role::where('name','client')->where('id',$role_id)->first();
                foreach($client_permissions as $permission_c){
                    $permission = Permission::where('name',$permission_c)->first();
                    $roles_c->givePermissionTo($permission);
                }
            }
            elseif($rolename == 'vendor')
            {
                $roles_v = Role::where('name','vendor')->where('id',$role_id)->first();
                foreach($vendor_permissions as $permission_v){
                    $permission = Permission::where('name',$permission_v)->first();
                    $roles_v->givePermissionTo($permission);
                }
            }
        }

    }
    public static function defaultdata($company_id = null,$workspace_id = null)
    {
        $company_setting = [
            "customer_prefix" => "#CUST",
            "vendor_prefix" => "#VEND",
            "bill_prefix" => "#BILL",
            "bill_starting_number" => "1",
            "bill_template" => "template1",
        ];
        if(!empty($company_id)){
            $vendor_role = Role::where('name','vendor')->where('created_by',$company_id)->where('guard_name','web')->first();
            if(empty($vendor_role))
            {
                $vendor_role                   = new Role();
                $vendor_role->name             = 'vendor';
                $vendor_role->guard_name       = 'web';
                $vendor_role->module           = 'Base';
                $vendor_role->created_by       = $company_id;
                $vendor_role->save();

            }
            if(!empty($workspace_id)){
                $bank_account= New BankAccount();
                $bank_account->holder_name= 'cash';
                $bank_account->bank_name= '';
                $bank_account->account_number= '-';
                $bank_account->opening_balance='0.00';
                $bank_account->contact_number= '-';
                $bank_account->bank_address= '-';
                $bank_account->workspace= $workspace_id;
                $bank_account->created_by= $company_id;
                $bank_account->save();
            }
        }
        if($company_id == Null)
        {
            $companys = User::where('type','company')->get();
            foreach($companys as $company)
            {
                $WorkSpaces = WorkSpace::where('created_by',$company->id)->get();
                foreach($WorkSpaces as $WorkSpace)
                {
                    $bank=BankAccount::where('workspace',$WorkSpace->id)->where('created_by',$company->id)->first();
                    if(empty($bank)){
                        $bank_account= New BankAccount();
                        $bank_account->holder_name= 'cash';
                        $bank_account->bank_name= '';
                        $bank_account->account_number= '-';
                        $bank_account->opening_balance='0.00';
                        $bank_account->contact_number= '-';
                        $bank_account->bank_address= '-';
                        $bank_account->workspace= $WorkSpace->id;
                        $bank_account->created_by= $company->id;
                        $bank_account->save();
                    }

                    $userContext = new Context(['user_id' => $company->id,'workspace_id'=> !empty($WorkSpace->id) ? $WorkSpace->id : 0]);
                    foreach($company_setting as $key =>  $p)
                    {
                            \Settings::context($userContext)->set($key, $p);
                    }
                }
            }
        }elseif($workspace_id == Null){
            $company = User::where('type','company')->where('id',$company_id)->first();
            $WorkSpaces = WorkSpace::where('created_by',$company->id)->get();
            foreach($WorkSpaces as $WorkSpace)
            {
                $bank=BankAccount::where('workspace',$WorkSpace->id)->where('created_by',$company->id)->first();
                    if(empty($bank)){
                        $bank_account= New BankAccount();
                        $bank_account->holder_name= 'cash';
                        $bank_account->bank_name= '';
                        $bank_account->account_number= '-';
                        $bank_account->opening_balance='0.00';
                        $bank_account->contact_number= '-';
                        $bank_account->bank_address= '-';
                        $bank_account->workspace= $WorkSpace->id;
                        $bank_account->created_by= $company->id;
                        $bank_account->save();
                    }
                $userContext = new Context(['user_id' => $company->id,'workspace_id'=> !empty($WorkSpace->id) ? $WorkSpace->id : 0]);
                foreach($company_setting as $key =>  $p)
                {
                        \Settings::context($userContext)->set($key, $p);
                }
            }
        }else{
            $company = User::where('type','company')->where('id',$company_id)->first();
            $WorkSpace = WorkSpace::where('created_by',$company->id)->where('id',$workspace_id)->first();
            $userContext = new Context(['user_id' => $company->id,'workspace_id'=> !empty($WorkSpace->id) ? $WorkSpace->id : 0]);
            $bank=BankAccount::where('workspace',$WorkSpace->id)->where('created_by',$company->id)->first();
                    if(empty($bank)){
                        $bank_account= New BankAccount();
                        $bank_account->holder_name= 'cash';
                        $bank_account->bank_name= '';
                        $bank_account->account_number= '-';
                        $bank_account->opening_balance='0.00';
                        $bank_account->contact_number= '-';
                        $bank_account->bank_address= '-';
                        $bank_account->workspace= $WorkSpace->id;
                        $bank_account->created_by= $company->id;
                        $bank_account->save();
                    }
            foreach($company_setting as $key =>  $p)
            {
                    \Settings::context($userContext)->set($key, $p);
            }
        }
    }

    // start for chart-of-account
    public static $chartOfAccountType = [
        'assets' => 'Assets',
        'liabilities' => 'Liabilities',
        'equity' => 'Equity',
        'income' => 'Income',
        'costs of goods sold' => 'Costs of Goods Sold',
        'expenses' => 'Expenses',

    ];

    public static $chartOfAccountSubType = array(
        "assets" => array(
            '1' => 'Current Asset',
            '2' => 'Inventory Asset',
            '3' => 'Non-current Asset',
        ),
        "liabilities" => array(
            '1' => 'Current Liabilities',
            '2' => 'Long Term Liabilities',
            '3' => 'Share Capital',
            '4' => 'Retained Earnings',
        ),
        "equity" => array(
            '1' => 'Owners Equity',
        ),
        "income" => array(
            '1' => 'Sales Revenue',
            '2' => 'Other Revenue',
        ),
        "costs of goods sold" => array(
            '1' => 'Costs of Goods Sold',
        ),
        "expenses" => array(
            '1' => 'Payroll Expenses',
            '2' => 'General and Administrative expenses',
        ),

    );

    public static function chartOfAccount($type,$subType)
    {
        $accounts=
            [
                "Assets" => array(
                    'Current Asset' => array(
                        [
                            'code' => '1060',
                            'name' => 'Checking Account',
                        ],
                        [
                            'code' => '1065',
                            'name' => 'Petty Cash',
                        ],
                        [
                            'code' => '1200',
                            'name' => 'Account Receivables',
                        ],
                        [
                            'code' => '1205',
                            'name' => 'Allowance for doubtful accounts',
                        ],
                    ),
                    'Inventory Asset' => array(
                        [
                            'code' => '1510',
                            'name' => 'Inventory',
                        ],
                        [
                            'code' => '1520',
                            'name' => 'Stock of Raw Materials',
                        ],
                        [
                            'code' => '1530',
                            'name' => 'Stock of Work In Progress',
                        ],
                        [
                            'code' => '1540',
                            'name' => 'Stock of Finished Goods',
                        ],
                        [
                            'code' => '1550',
                            'name' => 'Goods Received Clearing account',
                        ],

                    ),
                    'Non-current Asset' =>array(
                        [
                            'code' => '1810',
                            'name' => 'Land and Buildings',
                        ],
                        [
                            'code' => '1820',
                            'name' => 'Office Furniture and Equipement',
                        ],
                        [
                            'code' => '1825',
                            'name' => 'Accum.depreciation-Furn. and Equip',
                        ],
                        [
                            'code' => '1840',
                            'name' => 'Motor Vehicle',
                        ],
                        [
                            'code' => '1845',
                            'name' => 'Accum.depreciation-Motor Vehicle',
                        ],

                    )
                ),
                "Liabilities" =>array(
                    'Current Liabilities' =>array(
                        [
                            'code' => '2100',
                            'name' => 'Account Payable',
                        ],
                        [
                            'code' => '2105',
                            'name' => 'Deferred Income',
                        ],
                        [
                            'code' => '2110',
                            'name' => 'Accrued Income Tax-Central',
                        ],
                        [
                            'code' => '2120',
                            'name' => 'Income Tax Payable',
                        ],
                        [
                            'code' => '2130',
                            'name' => 'Accrued Franchise Tax',
                        ],
                        [
                            'code' => '2140',
                            'name' => 'Vat Provision',
                        ],
                        [
                            'code' => '2145',
                            'name' => 'Purchase Tax',
                        ],
                        [
                            'code' => '2150',
                            'name' => 'VAT Pay / Refund',
                        ],
                        [
                            'code' => '2151',
                            'name' => 'Zero Rated',
                        ],
                        [
                            'code' => '2152',
                            'name' => 'Capital import',
                        ],
                        [
                            'code' => '2153',
                            'name' => 'Standard Import',
                        ],
                        [
                            'code' => '2154',
                            'name' => 'Capital Standard',
                        ],
                        [
                            'code' => '2155',
                            'name' => 'Vat Exempt',
                        ],
                        [
                            'code' => '2160',
                            'name' => 'Accrued Use Tax Payable',
                        ],
                        [
                            'code' => '2210',
                            'name' => 'Accrued Wages',
                        ],
                        [
                            'code' => '2220',
                            'name' => 'Accrued Comp Time',
                        ],
                        [
                            'code' => '2230',
                            'name' => 'Accrued Holiday Pay',
                        ],
                        [
                            'code' => '2240',
                            'name' => 'Accrued Vacation Pay',
                        ],
                        [
                            'code' => '2310',
                            'name' => 'Accr. Benefits - Central Provident Fund',
                        ],
                        [
                            'code' => '2320',
                            'name' => 'Accr. Benefits - Stock Purchase',
                        ],
                        [
                            'code' => '2330',
                            'name' => 'Accr. Benefits - Med, Den',
                        ],
                        [
                            'code' => '2340',
                            'name' => 'Accr. Benefits - Payroll Taxes',
                        ],
                        [
                            'code' => '2350',
                            'name' => 'Accr. Benefits - Credit Union',
                        ],
                        [
                            'code' => '2360',
                            'name' => 'Accr. Benefits - Savings Bond',
                        ],
                        [
                            'code' => '2370',
                            'name' => 'Accr. Benefits - Group Insurance',
                        ],
                        [
                            'code' => '2380',
                            'name' => 'Accr. Benefits - Charity Cont.',
                        ],
                    ),
                    'Long Term Liabilities'  => array(
                        [
                            'code' => '2620',
                            'name' => 'Bank Loans',
                        ],
                        [
                            'code' => '2680',
                            'name' => 'Loans from Shareholders',
                        ],
                    ),
                    'Share Capital' =>array(
                        [
                            'code' => '3350',
                            'name' => 'Common Shares',
                        ],
                    ),
                    'Retained Earnings' =>array(
                        [
                            'code' => '3590',
                            'name' => 'Reserves and Surplus',
                        ],
                        [
                            'code' => '3595',
                            'name' => 'Owners Drawings',
                        ],
                    ),
                ),
                "Equity" =>array(
                    'Owners Equity' =>array(
                        [
                            'code' => '3020',
                            'name' => 'Opening Balances and adjustments',
                        ],
                        [
                            'code' => '3025',
                            'name' => 'Owners Contribution',
                        ],
                        [
                            'code' => '3030',
                            'name' => 'Profit and Loss ( current Year)',
                        ],
                        [
                            'code' => '3035',
                            'name' => 'Retained income',
                        ],
                    ),
                ),
                "Income" => array(
                    'Sales Revenue' =>array(
                        [
                            'code' => '4010',
                            'name' => 'Sales Income',
                        ],
                        [
                            'code' => '4020',
                            'name' => 'Service Income',
                        ],
                    ),
                    'Other Revenue' =>array(
                        [
                            'code' => '4430',
                            'name' => 'Shipping and Handling',
                        ],
                        [
                            'code' => '4435',
                            'name' => 'Sundry Income',
                        ],
                        [
                            'code' => '4440',
                            'name' => 'Interest Received',
                        ],
                        [
                            'code' => '4450',
                            'name' => 'Foreign Exchange Gain',
                        ],
                        [
                            'code' => '4500',
                            'name' => 'Unallocated Income',
                        ],
                        [
                            'code' => '4510',
                            'name' => 'Discounts Received',
                        ],
                    ),
                ),
                "Costs of Goods Sold" =>array(
                    'Costs of Goods Sold' =>array(
                        [
                            'code' => '5005',
                            'name' => 'Cost of Sales- On Services',
                        ],
                        [
                            'code' => '5010',
                            'name' => 'Cost of Sales - Purchases',
                        ],
                        [
                            'code' => '5015',
                            'name' => 'Operating Costs',
                        ],
                        [
                            'code' => '5020',
                            'name' => 'Material Usage Varaiance',
                        ],
                        [
                            'code' => '5025',
                            'name' => 'Breakage and Replacement Costs',
                        ],
                        [
                            'code' => '5030',
                            'name' => 'Consumable Materials',
                        ],
                        [
                            'code' => '5035',
                            'name' => 'Sub-contractor Costs',
                        ],
                        [
                            'code' => '5040',
                            'name' => 'Purchase Price Variance',
                        ],
                        [
                            'code' => '5045',
                            'name' => 'Direct Labour - COS',
                        ],
                        [
                            'code' => '5050',
                            'name' => 'Purchases of Materials',
                        ],
                        [
                            'code' => '5060',
                            'name' => 'Discounts Received',
                        ],
                        [
                            'code' => '5100',
                            'name' => 'Freight Costs',
                        ],
                    ),
                ),
                "Expenses" =>array(
                    'Payroll Expenses' =>array(
                        [
                            'code' => '5410',
                            'name' => 'Salaries and Wages',
                        ],
                        [
                            'code' => '5415',
                            'name' => 'Directors Fees & Remuneration',
                        ],
                        [
                            'code' => '5420',
                            'name' => 'Wages - Overtime',
                        ],
                        [
                            'code' => '5425',
                            'name' => 'Members Salaries',
                        ],
                        [
                            'code' => '5430',
                            'name' => 'UIF Payments',
                        ],
                        [
                            'code' => '5440',
                            'name' => 'Payroll Taxes',
                        ],
                        [
                            'code' => '5450',
                            'name' => 'Workers Compensation ( Coida )',
                        ],
                        [
                            'code' => '5460',
                            'name' => 'Normal Taxation Paid',
                        ],
                        [
                            'code' => '5470',
                            'name' => 'General Benefits',
                        ],
                        [
                            'code' => '5510',
                            'name' => 'Provisional Tax Paid',
                        ],
                        [
                            'code' => '5520',
                            'name' => 'Inc Tax Exp - State',
                        ],
                        [
                            'code' => '5530',
                            'name' => 'Taxes - Real Estate',
                        ],
                        [
                            'code' => '5540',
                            'name' => 'Taxes - Personal Property',
                        ],
                        [
                            'code' => '5550',
                            'name' => 'Taxes - Franchise',
                        ],
                        [
                            'code' => '5560',
                            'name' => 'Taxes - Foreign Withholding',
                        ],
                    ),
                    'General and Administrative expenses' =>array(
                        [
                            'code' => '5610',
                            'name' => 'Accounting Fees',
                        ],
                        [
                            'code' => '5615',
                            'name' => 'Advertising and Promotions',
                        ],
                        [
                            'code' => '5620',
                            'name' => 'Bad Debts',
                        ],
                        [
                            'code' => '5625',
                            'name' => 'Courier and Postage',
                        ],
                        [
                            'code' => '5660',
                            'name' => 'Depreciation Expense',
                        ],
                        [
                            'code' => '5685',
                            'name' => 'Insurance Expense',
                        ],
                        [
                            'code' => '5690',
                            'name' => 'Bank Charges',
                        ],
                        [
                            'code' => '5695',
                            'name' => 'Interest Paid',
                        ],
                        [
                            'code' => '5700',
                            'name' => 'Office Expenses - Consumables',
                        ],
                        [
                            'code' => '5705',
                            'name' => 'Printing and Stationary',
                        ],
                        [
                            'code' => '5710',
                            'name' => 'Security Expenses',
                        ],
                        [
                            'code' => '5715',
                            'name' => 'Subscription - Membership Fees',
                        ],
                        [
                            'code' => '5755',
                            'name' => 'Electricity, Gas and Water',
                        ],
                        [
                            'code' => '5760',
                            'name' => 'Rent Paid',
                        ],
                        [
                            'code' => '5765',
                            'name' => 'Repairs and Maintenance',
                        ],
                        [
                            'code' => '5770',
                            'name' => 'Motor Vehicle Expenses',
                        ],
                        [
                            'code' => '5771',
                            'name' => 'Petrol and Oil',
                        ],
                        [
                            'code' => '5775',
                            'name' => 'Equipment Hire - Rental',
                        ],
                        [
                            'code' => '5780',
                            'name' => 'Telephone and Internet',
                        ],
                        [
                            'code' => '5785',
                            'name' => 'Travel and Accommodation',
                        ],
                        [
                            'code' => '5786',
                            'name' => 'Meals and Entertainment',
                        ],
                        [
                            'code' => '5787',
                            'name' => 'Staff Training',
                        ],
                        [
                            'code' => '5790',
                            'name' => 'Utilities',
                        ],
                        [
                            'code' => '5791',
                            'name' => 'Computer Expenses',
                        ],
                        [
                            'code' => '5795',
                            'name' => 'Registrations',
                        ],
                        [
                            'code' => '5800',
                            'name' => 'Licenses',
                        ],
                        [
                            'code' => '5810',
                            'name' => 'Foreign Exchange Loss',
                        ],
                        [
                            'code' => '9990',
                            'name' => 'Profit and Loss',
                        ],
                    ),
                ),
            ];
        return $accounts[$type][$subType];
    }

    public static function defaultChartAccountdata($company_id = null,$workspace_id = null)
    {
        if($company_id == Null)
        {

            $companys = User::where('type','company')->get();

            foreach($companys as $company) {
                $WorkSpaces = WorkSpace::where('created_by',$company->id)->get();

                foreach($WorkSpaces as $WorkSpace)
                {
                    $chartOfAccountTypes = Self::$chartOfAccountType;
                    foreach ($chartOfAccountTypes as $k => $type)
                    {
                        //when ChartOfAccountType data empty
                        $check_type = ChartOfAccountType::where('workspace', $WorkSpace->id)->where('created_by', $company->id)->where('name',$type)->first();
                        if (empty($check_type)) {
                            $accountType = ChartOfAccountType::create(
                                [
                                    'name' => $type,
                                    'workspace' => $WorkSpace->id,
                                    'created_by' => $company->id,
                                ]
                            );

                            //when ChartOfAccountSubType data empty
                            $chartOfAccountSubTypes = Self::$chartOfAccountSubType;
                            foreach ($chartOfAccountSubTypes[$k] as $subType)
                            {
                                $check_subtype = ChartOfAccountSubType::where('workspace', $WorkSpace->id)->where('created_by', $company->id)->where('type',$accountType->id)->where('name',$subType)->first();
                                if (empty($check_subtype))
                                {
                                    $accountSubType=  ChartOfAccountSubType::create(
                                        [
                                            'name' => $subType,
                                            'type' => $accountType->id,
                                            'workspace' => $WorkSpace->id,
                                            'created_by' => $company->id,
                                        ]
                                    );

                                    //when ChartOfAccount data empty
                                    $chartOfAccounts = AccountUtility::chartOfAccount($type,$subType);
                                    foreach ($chartOfAccounts as $chartAccount)
                                    {
                                        $check_account = ChartOfAccount::where('workspace', $WorkSpace->id)
                                            ->where('created_by', $company->id)->where('type',$accountType->id)
                                            ->where('name',$subType)->where('name',$chartAccount['name'])->first();

                                        if (empty($check_account)) {
                                            ChartOfAccount::create(
                                                [
                                                    'name' => $chartAccount['name'],
                                                    'code' => $chartAccount['code'],
                                                    'type' => $accountType->id,
                                                    'sub_type' => $accountSubType->id,
                                                    'is_enabled' => 1,
                                                    'workspace' => $WorkSpace->id,
                                                    'created_by' => $company->id,

                                                ]
                                            );

                                        }
                                    }

                                }
                            }
                        }


                    }
                }
            }
        }
        elseif($workspace_id == Null)
        {
            $company = User::where('type','company')->where('id',$company_id)->first();
            $WorkSpaces = WorkSpace::where('created_by',$company->id)->get();
            foreach($WorkSpaces as $WorkSpace)
            {
                $chartOfAccountTypes = Self::$chartOfAccountType;
                foreach ($chartOfAccountTypes as $k => $type)
                {
                    //when ChartOfAccountType data empty
                    $check_type = ChartOfAccountType::where('workspace', $WorkSpace->id)->where('created_by', $company->id)->where('name',$type)->first();
                    if (empty($check_type)) {
                        $accountType = ChartOfAccountType::create(
                            [
                                'name' => $type,
                                'workspace' => $WorkSpace->id,
                                'created_by' => $company->id,
                            ]
                        );

                        $chartOfAccountSubTypes = Self::$chartOfAccountSubType;
                        foreach ($chartOfAccountSubTypes[$k] as $subType) {
                            //when ChartOfAccountSubType data empty
                            $check_subtype = ChartOfAccountSubType::where('workspace', $WorkSpace->id)->where('created_by', $company->id)->where('type',$accountType->id)->where('name',$subType)->first();
                            if (empty($check_subtype)) {
                                $accountSubType = ChartOfAccountSubType::create(
                                    [
                                        'name' => $subType,
                                        'type' => $accountType->id,
                                        'workspace' => $WorkSpace->id,
                                        'created_by' => $company->id,
                                    ]
                                );

                                //when ChartOfAccount data empty
                                $chartOfAccounts = AccountUtility::chartOfAccount($type,$subType);
                                foreach ($chartOfAccounts as $chartAccount)
                                {
                                    $check_account = ChartOfAccount::where('workspace', $WorkSpace->id)
                                        ->where('created_by', $company->id)->where('type',$accountType->id)
                                        ->where('name',$subType)->where('name',$chartAccount['name'])->first();

                                    if (empty($check_account)) {
                                        ChartOfAccount::create(
                                            [
                                                'name' => $chartAccount['name'],
                                                'code' => $chartAccount['code'],
                                                'type' => $accountType->id,
                                                'sub_type' => $accountSubType->id,
                                                'is_enabled' => 1,
                                                'workspace' => $WorkSpace->id,
                                                'created_by' => $company->id,

                                            ]
                                        );

                                    }
                                }
                            }
                        }
                    }
                }

            }
        }
        else {

            $company = User::where('type', 'company')->where('id', $company_id)->first();
            $WorkSpace = WorkSpace::where('created_by', $company->id)->where('id', $workspace_id)->first();
            $chartOfAccountTypes = Self::$chartOfAccountType;
            foreach ($chartOfAccountTypes as $k => $type)
            {
                //when ChartOfAccountType data empty
                $check_type = ChartOfAccountType::where('workspace', $WorkSpace->id)->where('created_by', $company->id)->where('name',$type)->first();
                if (empty($check_type))
                {
                    $accountType = ChartOfAccountType::create(
                        [
                            'name' => $type,
                            'workspace' => $WorkSpace->id,
                            'created_by' => $company->id,
                        ]
                    );


                    //when ChartOfAccountSubType data empty
                    $chartOfAccountSubTypes = Self::$chartOfAccountSubType;
                    foreach ($chartOfAccountSubTypes[$k] as $subType)
                    {
                        $check_subtype = ChartOfAccountSubType::where('workspace', $WorkSpace->id)->where('created_by', $company->id)->where('type',$accountType->id)->where('name',$subType)->first();
                        if (empty($check_subtype))
                        {
                            $accountSubType = ChartOfAccountSubType::create(
                                [
                                    'name' => $subType,
                                    'type' => $accountType->id,
                                    'workspace' => $WorkSpace->id,
                                    'created_by' => $company->id,
                                ]
                            );

                            //when ChartOfAccount data empty
                            $chartOfAccounts = AccountUtility::chartOfAccount($type,$subType);
                            foreach ($chartOfAccounts as $chartAccount)
                            {
                                $check_account = ChartOfAccount::where('workspace', $WorkSpace->id)
                                    ->where('created_by', $company->id)->where('type',$accountType->id)
                                    ->where('name',$subType)->where('name',$chartAccount['name'])->first();

                                if (empty($check_account)) {
                                    ChartOfAccount::create(
                                        [
                                            'name' => $chartAccount['name'],
                                            'code' => $chartAccount['code'],
                                            'type' => $accountType->id,
                                            'sub_type' => $accountSubType->id,
                                            'is_enabled' => 1,
                                            'workspace' => $WorkSpace->id,
                                            'created_by' => $company->id,

                                        ]
                                    );

                                }
                            }
                        }
                    }
                }
            }
        }
    }

    public static function getAccountBalance($account_id,$start_date=null,$end_date=null)
    {

        if(!empty($start_date) && !empty($end_date))
        {
            $start = $start_date;
            $end   = $end_date;
        }
        else
        {
            $start = date('Y-m-01');
            $end   = date('Y-m-t');
        }

        $invoiceAmount = [];
        if(module_is_active('ProductService'))
        {
            $invoice_product = \Modules\ProductService\Entities\ProductService::where('sale_chartaccount_id',$account_id)->get()->pluck('id');
            $invoiceData = InvoiceProduct::select(DB::raw('sum(price * quantity) as amount'));
            if(!empty($start_date) && !empty($end_date))
            {
                $invoiceData->where('created_at', '>=', $start);
                $invoiceData->where('created_at', '<=', $end);
            }
            $invoiceData=$invoiceData->whereIn('product_id',$invoice_product)->first();

        }
        $invoiceAmount=!empty($invoiceData->amount)?$invoiceData->amount:0;


        $getAccount = BankAccount::where('chart_account_id',$account_id)->get()->pluck('id');

        $invoicePaymentAmount = InvoicePayment::whereIn('account_id',$getAccount);
        if(!empty($start_date) && !empty($end_date))
        {
            $invoicePaymentAmount->where('date', '>=', $start);
            $invoicePaymentAmount->where('date', '<=', $end);
        }
        $invoicePaymentAmount= $invoicePaymentAmount->sum('amount');

        $revenueAmount = Revenue::whereIn('account_id',$getAccount);
        if(!empty($start_date) && !empty($end_date))
        {
            $revenueAmount->where('date', '>=', $start);
            $revenueAmount->where('date', '<=', $end);
        }
        $revenueAmount= $revenueAmount->sum('amount');


        $bill_product=[];
        if(module_is_active('ProductService'))
        {
            $bill_product = \Modules\ProductService\Entities\ProductService::where('expense_chartaccount_id',$account_id)->get()->pluck('id');

        }

        $billData = BillProduct::select(DB::raw('sum(price * quantity) as amount'));


        if(!empty($start_date) && !empty($end_date))
        {
            $billData->where('created_at', '>=', $start);
            $billData->where('created_at', '<=', $end);
        }
        $billData=$billData->whereIn('product_id',$bill_product)->first();

        $billProductAmount=!empty($billData->amount)?$billData->amount:0;

        $billAmount = BillAccount::where('chart_account_id',$account_id);
        if(!empty($start_date) && !empty($end_date))
        {
            $billAmount->where('created_at', '>=', $start);
            $billAmount->where('created_at', '<=', $end);
        }
        $billAmount= $billAmount->sum('price');

        $billPaymentAmount =BillPayment::whereIn('account_id',$getAccount);
        if(!empty($start_date) && !empty($end_date))
        {
            $billPaymentAmount->where('date', '>=', $start);
            $billPaymentAmount->where('date', '<=', $end);
        }
        $billPaymentAmount= $billPaymentAmount->sum('amount');

        $paymentAmount = Payment::whereIn('account_id',$getAccount);
        if(!empty($start_date) && !empty($end_date))
        {
            $paymentAmount->where('date', '>=', $start);
            $paymentAmount->where('date', '<=', $end);
        }
        $paymentAmount= $paymentAmount->sum('amount');

        $journalCredit = [];
        $journalDebit = [];
        if(module_is_active('DoubleEntry'))
        {
            $journalCredit = \Modules\DoubleEntry\Entities\JournalItem::select('journal_entries.journal_id', 'journal_entries.date as transaction_date', 'journal_items.*')
                ->leftjoin('journal_entries', 'journal_entries.id', 'journal_items.journal')
                ->where('journal_entries.created_by', '=', creatorId())
                ->where('journal_entries.workspace', '=', getActiveWorkSpace())
                ->where('account', $account_id);
            $journalCredit->where('journal_items.created_at', '>=', $start);
            $journalCredit->where('journal_items.created_at', '<=', $end);
            $journalCredit = $journalCredit->sum('credit');

            $journalDebit = \Modules\DoubleEntry\Entities\JournalItem::select('journal_entries.journal_id', 'journal_entries.date as transaction_date', 'journal_items.*')
                ->leftjoin('journal_entries', 'journal_entries.id', 'journal_items.journal')
                ->where('journal_entries.created_by', '=', creatorId())
                ->where('journal_entries.workspace', '=', getActiveWorkSpace())
                ->where('account', $account_id);
            $journalDebit->where('journal_items.created_at', '>=', $start);
            $journalDebit->where('journal_items.created_at', '<=', $end);
            $journalDebit = $journalDebit->sum('debit');
        }


        $journalCredit = !empty($journalCredit)?$journalCredit:0;
        $journalDebit = !empty($journalDebit)?$journalDebit:0;


        $balance   =  ($invoiceAmount + $invoicePaymentAmount + $revenueAmount + $journalCredit) - ($journalDebit + $billProductAmount + $billAmount + $billPaymentAmount + $paymentAmount);

        return $balance;

    }

    public static function getAccountData($account_id,$start_date=null,$end_date=null)
    {

        if(!empty($start_date) && !empty($end_date))
        {
            $start = $start_date;
            $end   = $end_date;
        }
        else
        {
            $start = date('Y-m-01');
            $end   = date('Y-m-t');
        }

        $invoice = [];
        if(module_is_active('ProductService'))
        {
            $invoice_product =  \Modules\ProductService\Entities\ProductService::where('sale_chartaccount_id',$account_id)->get()->pluck('id');
            $invoice = InvoiceProduct::whereIn('product_id',$invoice_product);
            if(!empty($start_date) && !empty($end_date))
            {
                $invoice->where('created_at', '>=', $start);
                $invoice->where('created_at', '<=', $end);
            }
            $invoice = $invoice->get();
        }

        $getAccount   = BankAccount::where('chart_account_id',$account_id)->get()->pluck('id');

        $invoicePayment = InvoicePayment::whereIn('account_id',$getAccount);
        if(!empty($start_date) && !empty($end_date))
        {
            $invoicePayment->where('date', '>=', $start);
            $invoicePayment->where('date', '<=', $end);
        }
        $invoicePayment= $invoicePayment->get();


        $revenue = Revenue::whereIn('account_id',$getAccount);
        if(!empty($start_date) && !empty($end_date))
        {
            $revenue->where('date', '>=', $start);
            $revenue->where('date', '<=', $end);
        }
        $revenue= $revenue->get();

        $bill_product = [];
        if(module_is_active('ProductService'))
        {
            $bill_product = \Modules\ProductService\Entities\ProductService::where('expense_chartaccount_id',$account_id)->get()->pluck('id');

        }

        $bill = BillProduct::whereIn('product_id',$bill_product);
        if(!empty($start_date) && !empty($end_date))
        {
            $bill->where('created_at', '>=', $start);
            $bill->where('created_at', '<=', $end);
        }
        $bill= $bill->get();

        $billData = BillAccount::where('chart_account_id',$account_id);
        if(!empty($start_date) && !empty($end_date))
        {
            $billData->where('created_at', '>=', $start);
            $billData->where('created_at', '<=', $end);
        }
        $billData= $billData->get();


        $billPayment= BillPayment::whereIn('account_id',$getAccount);
        if(!empty($start_date) && !empty($end_date))
        {
            $billPayment->where('date', '>=', $start);
            $billPayment->where('date', '<=', $end);
        }
        $billPayment= $billPayment->get();

        $payment =Payment::whereIn('account_id',$getAccount);
        if(!empty($start_date) && !empty($end_date))
        {
            $payment->where('date', '>=', $start);
            $payment->where('date', '<=', $end);
        }
        $payment= $payment->get();


        $journalItems  = [];
        if(module_is_active('DoubleEntry'))
        {
            $journalItems = \Modules\DoubleEntry\Entities\JournalItem::select('journal_entries.journal_id', 'journal_entries.date as transaction_date', 'journal_items.*')
                ->leftjoin('journal_entries', 'journal_entries.id', 'journal_items.journal')
                ->where('journal_entries.created_by', '=', creatorId())->where('journal_entries.workspace', '=', getActiveWorkSpace())
                ->where('account', $account_id);
            $journalItems->where('date', '>=', $start);
            $journalItems->where('date', '<=', $end);
            $journalItems = $journalItems->get();
        }

        $data=[];
        $data['invoice'] = $invoice;
        $data['invoicepayment'] = $invoicePayment;
        $data['revenue'] = $revenue;
        $data['bill'] = $bill;
        $data['billdata'] = $billData;
        $data['billpayment'] = $billPayment;
        $data['payment'] = $payment;
        $data['journalItem'] = $journalItems;

        return $data;

    }


    public static function trialBalance($account_id, $start, $end)
    {

        $journalItem  = [];
        if(module_is_active('DoubleEntry')) {
            $journalItem = \Modules\DoubleEntry\Entities\JournalItem::select('chart_of_accounts.id', 'chart_of_accounts.code', 'chart_of_accounts.name', \DB::raw('sum(debit) as totalDebit'), \DB::raw('sum(credit) as totalCredit'));
            $journalItem->leftjoin('journal_entries', 'journal_entries.id', 'journal_items.journal');
            $journalItem->leftjoin('chart_of_accounts', 'journal_items.account', 'chart_of_accounts.id');
            $journalItem->where('chart_of_accounts.type', $account_id);
            $journalItem->where('chart_of_accounts.created_by',  creatorId());
            $journalItem->where('chart_of_accounts.workspace',  getActiveWorkSpace());
            $journalItem->whereBetween('journal_entries.date', [$start, $end]);
            $journalItem->groupBy('account');
            $journalItem = $journalItem->get()->toArray();
        }



        $invoice = InvoiceProduct::select('chart_of_accounts.id','chart_of_accounts.code','chart_of_accounts.name' , \DB::raw('0 as totalDebit'), \DB::raw('sum(price*invoice_products.quantity) as totalCredit'));
        $invoice->leftjoin('product_services','product_services.id','invoice_products.product_id');
        $invoice->leftjoin('chart_of_accounts', 'product_services.sale_chartaccount_id', 'chart_of_accounts.id');
        $invoice->leftjoin('invoices','invoices.id','invoice_products.invoice_id');
        $invoice->where('chart_of_accounts.type',$account_id);
        $invoice->where('chart_of_accounts.created_by', creatorId());
        $invoice->where('chart_of_accounts.workspace', getActiveWorkSpace());
        $invoice->whereBetween('invoices.issue_date', [$start, $end]);
        $invoice->groupBy('product_services.sale_chartaccount_id');
        $invoice = $invoice->get()->toArray();



        $invoicePayment = InvoicePayment::select('chart_of_accounts.id','chart_of_accounts.code','chart_of_accounts.name' , \DB::raw('sum(amount) as totalDebit'), \DB::raw('0 as totalCredit'));
        $invoicePayment->leftjoin('bank_accounts','bank_accounts.id','invoice_payments.account_id');
        $invoicePayment->leftjoin('chart_of_accounts', 'bank_accounts.chart_account_id', 'chart_of_accounts.id');
        $invoicePayment->where('chart_of_accounts.type',$account_id);
        $invoicePayment->where('chart_of_accounts.created_by', creatorId());
        $invoicePayment->where('chart_of_accounts.workspace', getActiveWorkSpace());
        $invoicePayment->whereBetween('invoice_payments.date', [$start, $end]);
        $invoicePayment->groupBy('account_id');
        $invoicePayment = $invoicePayment->get()->toArray();


        $revenue = Revenue::select('chart_of_accounts.id','chart_of_accounts.code','chart_of_accounts.name' , \DB::raw('0 as totalDebit'), \DB::raw('sum(amount) as totalCredit'));
        $revenue->leftjoin('bank_accounts','bank_accounts.id','revenues.account_id');
        $revenue->leftjoin('chart_of_accounts', 'bank_accounts.chart_account_id', 'chart_of_accounts.id');
        $revenue->where('chart_of_accounts.type',$account_id);
        $revenue->where('chart_of_accounts.created_by', creatorId());
        $revenue->where('chart_of_accounts.workspace', getActiveWorkSpace());
        $revenue->whereBetween('date', [$start, $end]);
        $revenue->groupBy('chart_account_id');
        $revenue = $revenue->get()->toArray();

        $bill = BillProduct::select('chart_of_accounts.id','chart_of_accounts.code','chart_of_accounts.name' , \DB::raw('sum(price*bill_products.quantity) as totalDebit') , \DB::raw('0 as totalCredit'));
        $bill->leftjoin('product_services','product_services.id','bill_products.product_id');
        $bill->leftjoin('bills','bills.id','bill_products.bill_id');
        $bill->leftjoin('chart_of_accounts', 'product_services.expense_chartaccount_id', 'chart_of_accounts.id');
        $bill->where('chart_of_accounts.type',$account_id);
        $bill->where('chart_of_accounts.created_by', creatorId());
        $bill->where('chart_of_accounts.workspace', getActiveWorkSpace());
        $bill->whereBetween('bills.bill_date', [$start, $end]);
        $bill->groupBy('product_services.expense_chartaccount_id');
        $bill = $bill->get()->toArray();


        $billAccount = BillAccount::select('chart_of_accounts.id','chart_of_accounts.code','chart_of_accounts.name' , \DB::raw('sum(price) as totalDebit'), \DB::raw('0 as totalCredit'));
        $billAccount->leftjoin('chart_of_accounts', 'bill_accounts.chart_account_id', 'chart_of_accounts.id');
        $billAccount->leftjoin('bills','bills.id','bill_accounts.ref_id');
        $billAccount->where('chart_of_accounts.type',$account_id);
        $billAccount->where('chart_of_accounts.created_by', creatorId());
        $billAccount->where('chart_of_accounts.workspace', getActiveWorkSpace());
        $billAccount->whereBetween('bills.bill_date', [$start, $end]);
        $billAccount->groupBy('chart_account_id');
        $billAccount = $billAccount->get()->toArray();

        $billPayment = BillPayment::select('chart_of_accounts.id','chart_of_accounts.code','chart_of_accounts.name' , \DB::raw('0 as totalDebit'), \DB::raw('sum(amount) as totalCredit'));
        $billPayment->leftjoin('bank_accounts','bank_accounts.id','bill_payments.account_id');
        $billPayment->leftjoin('chart_of_accounts', 'bank_accounts.chart_account_id', 'chart_of_accounts.id');
        $billPayment->where('chart_of_accounts.type',$account_id);
        $billPayment->where('chart_of_accounts.created_by', creatorId());
        $billPayment->where('chart_of_accounts.workspace', getActiveWorkSpace());
        $billPayment->whereBetween('bill_payments.date', [$start, $end]);
        $billPayment->groupBy('account_id');
        $billPayment = $billPayment->get()->toArray();

        $payments = Payment::select('chart_of_accounts.id','chart_of_accounts.code','chart_of_accounts.name' , \DB::raw('sum(amount) as totalDebit'), \DB::raw('0 as totalCredit'));
        $payments->leftjoin('bank_accounts','bank_accounts.id','payments.account_id');
        $payments->leftjoin('chart_of_accounts', 'bank_accounts.chart_account_id', 'chart_of_accounts.id');
        $payments->where('chart_of_accounts.type',$account_id);
        $payments->where('chart_of_accounts.created_by', creatorId());
        $payments->where('chart_of_accounts.workspace', getActiveWorkSpace());
        $payments->whereBetween('payments.date', [$start, $end]);
        $payments->groupBy('account_id');
        $payments = $payments->get()->toArray();

        $total = array_merge($invoice , $journalItem , $revenue , $bill ,$billAccount , $payments, $invoicePayment , $billPayment);

        return $total;
    }

    // end for chart-of-account
}
