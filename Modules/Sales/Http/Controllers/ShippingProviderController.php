<?php

namespace Modules\Sales\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Sales\Entities\Quote;
use Modules\Sales\Entities\SalesInvoice;
use Modules\Sales\Entities\SalesOrder;
use Modules\Sales\Entities\ShippingProvider;
use Modules\Sales\Events\CreateShippingProvider;
use Modules\Sales\Events\DestroyShippingProvider;
use Modules\Sales\Events\UpdateShippingProvider;

class ShippingProviderController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if (\Auth::user()->can('shippingprovider manage')) {
            $shipping_providers = ShippingProvider::where('created_by', creatorId())->where('workspace', getActiveWorkSpace())->get();

            return view('sales::shipping_provider.index', compact('shipping_providers'));
        } else {
            return redirect()->back()->with('error', 'permission Denied');
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        if (\Auth::user()->can('shippingprovider create')) {
            return view('sales::shipping_provider.create');
        } else {
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
        if (\Auth::user()->can('shippingprovider create')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'name' => 'required|max:120',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $shippingprovider               = new ShippingProvider();
            $shippingprovider->name         = $request['name'];
            $shippingprovider->website      = $request['website'];
            $shippingprovider['workspace']  = getActiveWorkSpace();
            $shippingprovider['created_by'] = creatorId();
            $shippingprovider->save();
            event(new CreateShippingProvider($request, $shippingprovider));

            return redirect()->route('shipping_provider.index')->with('success', 'Shipping Provider successfully created.');
        } else {
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
    public function edit(ShippingProvider $shippingProvider)
    {
        if (\Auth::user()->can('shippingprovider edit')) {
            return view('sales::shipping_provider.edit', compact('shippingProvider'));
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, ShippingProvider $shippingProvider)
    {
        if (\Auth::user()->can('shippingprovider edit')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'name' => 'required|max:120',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            $name                           = $request['name'];
            $shippingProvider->name         = $name;
            $shippingProvider->website      = $request['website'];
            $shippingProvider['workspace']  = getActiveWorkSpace();
            $shippingProvider['created_by'] = creatorId();
            $shippingProvider->update();
            event(new UpdateShippingProvider($request, $shippingProvider));

            return redirect()->route('shipping_provider.index')->with('success', 'Shipping Provider successfully updated.');
        } else {
            return redirect()->back()->with('error', 'permission Denied');
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy(ShippingProvider $shippingProvider)
    {
        if (\Auth::user()->can('shippingprovider delete')) {
            $quote = Quote::where('shipping_provider', '=', $shippingProvider->id)->count();
            $salesorder = SalesOrder::where('shipping_provider', '=', $shippingProvider->id)->count();
            $salesinvoice = SalesInvoice::where('shipping_provider', '=', $shippingProvider->id)->count();
            if ($quote == 0 && $salesorder == 0 && $salesinvoice == 0) {
                event(new DestroyShippingProvider($shippingProvider));

                $shippingProvider->delete();

                return redirect()->route('shipping_provider.index')->with(
                    'success',
                    'Shipping Provider successfully deleted.'
                );
            } else {
                return redirect()->back()->with('error', 'This Shipping Provides is Used on Quote , Sales Order and Sales Invoice');
            }
        } else {
            return redirect()->back()->with('error', 'permission Denied');
        }
    }
}
