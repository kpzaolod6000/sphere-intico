<?php

namespace Modules\Account\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Account\Entities\ChartOfAccount;
use Modules\Account\Entities\ChartOfAccountSubType;
use Modules\Account\Entities\ChartOfAccountType;
use Modules\Account\Events\CreateChartAccount;
use Modules\Account\Events\DestroyChartAccount;
use Modules\Account\Events\UpdateChartAccount;
use Modules\DoubleEntry\Entities\DoubleEntryUtility;

class ChartOfAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */

    public function index(Request $request)
    {
        if(\Auth::user()->can('chart of account manage'))
        {

            if (!empty($request->start_date) && !empty($request->end_date)) {
                $start = $request->start_date;
                $end = $request->end_date;
            } else {
                $start = date('Y-01-01');
                $end = date('Y-m-d', strtotime('+1 day'));
            }

            $filter['startDateRange'] = $start;
            $filter['endDateRange'] = $end;

            $types = ChartOfAccountType::where('created_by', '=',creatorId())->where('workspace',getActiveWorkSpace())->get();
            $chartAccounts = [];
            foreach($types as $type)
            {
                $accounts = ChartOfAccount::where('type', $type->id)->where('created_by', '=',creatorId())->where('workspace',getActiveWorkSpace())->get();
                $chartAccounts[$type->name] = $accounts;

            }

            return view('account::chartOfAccount.index', compact('chartAccounts', 'types','filter'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $types = ChartOfAccountType::where('created_by', '=',creatorId())->where('workspace',getActiveWorkSpace())->get()->pluck('name', 'id');
        $types->prepend('Select Account Type', 0);

        return view('account::chartOfAccount.create', compact('types'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {

        if(\Auth::user()->can('chart of account create'))
        {
            $validator = \Validator::make(
                $request->all(), [
                    'name' => 'required',
                    'type' => 'required',
                ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $account              = new ChartOfAccount();
            $account->name        = $request->name;
            $account->code        = $request->code;
            $account->type        = $request->type;
            $account->sub_type    = $request->sub_type;
            $account->description = $request->description;
            $account->is_enabled  = isset($request->is_enabled) ? 1 : 0;
            $account->created_by  = creatorId();
            $account->workspace     = getActiveWorkSpace();
            $account->save();

            event(new CreateChartAccount($request,$account));

            return redirect()->back()->with('success', __('Account successfully created.'));

        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show(ChartOfAccount $chartOfAccount,Request $request)
    {
        if(\Auth::user()->can('report ledger'))
        {
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

            if(!empty($request->start_date) && !empty($request->end_date))
            {
                $accounts = ChartOfAccount::select(\DB::raw('CONCAT(code, " - ", name) AS code_name, id'))
                    ->where('created_by', creatorId())
                    ->where('created_at', '>=', $start)
                    ->where('created_at', '<=', $end)
                    ->get()->pluck('code_name', 'id');
                $accounts->prepend('Select Account', '');

            }
            else
            {
                $accounts = ChartOfAccount::select(\DB::raw('CONCAT(code, " - ", name) AS code_name, id'))
                    ->where('created_by', creatorId())->get()
                    ->pluck('code_name', 'id');
                $accounts->prepend('Select Account', '');
            }

            if(!empty($request->account))
            {
                $account = ChartOfAccount::find($request->account);
            }
            else
            {
                $account = ChartOfAccount::find($chartOfAccount->id);
            }

            $filter['startDateRange'] = $start;
            $filter['endDateRange']   = $end;

            return view('account::chartOfAccount.show', compact('filter', 'account', 'accounts'));

        }
        else
        {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit(ChartOfAccount $chartOfAccount)
    {

        $types = ChartOfAccountType::get()->pluck('name', 'id');
        $types->prepend('Select Account Type', 0);
        return view('account::chartOfAccount.edit',compact('chartOfAccount', 'types'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */


    public function update(Request $request, ChartOfAccount $chartOfAccount)
    {

        if(\Auth::user()->can('chart of account edit'))
        {
            $validator = \Validator::make(
                $request->all(), [
                    'name' => 'required',
                ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }

            $chartOfAccount->name        = $request->name;
            $chartOfAccount->code        = $request->code;
            $chartOfAccount->description = $request->description;
            $chartOfAccount->is_enabled  = isset($request->is_enabled) ? 1 : 0;
            $chartOfAccount->save();

            event(new UpdateChartAccount($request,$chartOfAccount));

            return redirect()->route('chart-of-account.index')->with('success', __('Account successfully updated.'));
        }
        else
        {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }


    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy(ChartOfAccount $chartOfAccount)
    {

        if(\Auth::user()->can('chart of account delete'))
        {
            $chartOfAccount->delete();

            event(new DestroyChartAccount($chartOfAccount));


            return redirect()->route('chart-of-account.index')->with('success', __('Account successfully deleted.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function getSubType(Request $request)
    {
        $types = ChartOfAccountSubType::where('type', $request->type)->get()->pluck('name', 'id')->toArray();

        return response()->json($types);
    }
}
