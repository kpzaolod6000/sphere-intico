<?php

namespace Modules\Sales\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Sales\Entities\AccountIndustry;
use Modules\Sales\Entities\SalesAccount;
use Modules\Sales\Events\CreateSalesAccountIndustry;
use Modules\Sales\Events\DestroySalesAccountIndustry;
use Modules\Sales\Events\UpdateSalesAccountIndustry;

class SalesAccountIndustryController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if(\Auth::user()->can('accountindustry manage'))
        {
        $industrys = AccountIndustry::where('created_by', creatorId())->where('workspace',getActiveWorkSpace())->get();

        return view('sales::account_industry.index', compact('industrys'));
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
    public function create()
    {
        if(\Auth::user()->can('accountindustry create'))
        {
        return view('sales::account_industry.create');
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
        if(\Auth::user()->can('accountindustry create'))
        {
            $validator = \Validator::make(
                $request->all(), ['name' => 'required|max:40',]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
        $name                          = $request['name'];
        $accountIndustry               = new accountIndustry();
        $accountIndustry->name         = $name;
        $accountIndustry['workspace'] = getActiveWorkSpace();
        $accountIndustry['created_by'] = creatorId();
        $accountIndustry->save();
        event(new CreateSalesAccountIndustry($request,$accountIndustry));

        return redirect()->route('account_industry.index')->with('success', 'Account Industry successfully created.');
        }
        else
        {
            return redirect()->back()->with('error', 'permission Denied');
        }
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
    public function edit(accountIndustry $accountIndustry)
    {
        if(\Auth::user()->can('accountindustry edit'))
        {
        return view('sales::account_industry.edit', compact('accountIndustry'));
        }
        else
        {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request,  accountIndustry $accountIndustry)
    {
        if(\Auth::user()->can('accountindustry edit'))
        {
            $validator = \Validator::make(
                $request->all(), ['name' => 'required|max:40',]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            $accountIndustry['name'] = $request->name;
            $accountIndustry['workspace'] = getActiveWorkSpace();
            $accountIndustry['created_by'] = creatorId();
            $accountIndustry->update();
            event(new UpdateSalesAccountIndustry($request,$accountIndustry));

            return redirect()->route('account_industry.index')->with(
                'success', 'Account Industry successfully updated.'
            );
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
    public function destroy(accountIndustry $accountIndustry)
    {
        if(\Auth::user()->can('accountindustry delete'))
        {

            $salesaccount = SalesAccount::where('industry', '=', $accountIndustry->id)->count();
            if($salesaccount == 0){
                event(new DestroySalesAccountIndustry($accountIndustry));

                $accountIndustry->delete();

                return redirect()->route('account_industry.index')->with('success', 'Account Industry successfully deleted.');
            }
            else
            {

                return redirect()->back()->with('error', 'This Account Industry is Used on Sales Account.');
            }

        }
        else
        {
            return redirect()->back()->with('error', 'permission Denied');
        }
    }
}
