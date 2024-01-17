<?php

namespace Modules\Sales\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Sales\Entities\SalesAccount;
use Modules\Sales\Entities\SalesAccountType;
use Modules\Sales\Events\CreateSalesAccountType;
use Modules\Sales\Events\DestroySalesAccountType;
use Modules\Sales\Events\UpdateSalesAccountType;

class SalesAccountTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if(\Auth::user()->can('salesaccounttype manage'))
        {
            $types = SalesAccountType::where('created_by', creatorId())->where('workspace',getActiveWorkSpace())->get();

            return view('sales::account_type.index', compact('types'));
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
        if(\Auth::user()->can('salesaccounttype create'))
        {
            return view('sales::account_type.create');
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
        if(\Auth::user()->can('salesaccounttype create'))
        {
            $validator = \Validator::make(
                $request->all(), ['name' => 'required|max:40',]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $name                      = $request['name'];
            $salesaccounttype               = new SalesAccountType();
            $salesaccounttype->name         = $name;
            $salesaccounttype->workspace = getActiveWorkSpace();
            $salesaccounttype['created_by'] = creatorId();

            $salesaccounttype->save();
            event(new CreateSalesAccountType($request,$salesaccounttype));

            return redirect()->route('account_type.index')->with('success', 'Account Type successfully created.');
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
       //
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     *@return \Illuminate\Http\Response
     */
    public function edit(Request $request,$id)
    {
        if(\Auth::user()->can('salesaccounttype edit'))
        {
            $salesaccounttype = SalesAccountType::find($id);
            return view('sales::account_type.edit', compact('salesaccounttype'));
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
    public function update(Request $request,$id)
    {
        if(\Auth::user()->can('salesaccounttype edit'))
        {
            $salesaccounttype = SalesAccountType::find($id);
            $validator = \Validator::make(
                $request->all(), ['name' => 'required|max:40',]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            $salesaccounttype['name']       = $request->name;
            $salesaccounttype['workspace'] = getActiveWorkSpace();
            $salesaccounttype['created_by'] = creatorId();
            $salesaccounttype->update();

            event(new UpdateSalesAccountType($request,$salesaccounttype));

            return redirect()->route('account_type.index')->with(
                'success', 'Account Type successfully updated.'
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
    public function destroy($id)
    {
        if(\Auth::user()->can('salesaccounttype delete'))
        {
            $salesaccount = SalesAccount::where('type', '=', $id)->count();
            if($salesaccount == 0){

                $salesaccounttype = SalesAccountType::find($id);
                $salesaccounttype->delete();
                event(new DestroySalesAccountType($salesaccounttype));

                return redirect()->route('account_type.index')->with(
                    'success', 'Account Type successfully deleted.'
                );
            }
            else
            {
                return redirect()->back()->with('error', 'This Account Type is Used on Sales Account.');
            }
        }
        else
        {
            return redirect()->back()->with('error', 'permission Denied');
        }
    }
}
