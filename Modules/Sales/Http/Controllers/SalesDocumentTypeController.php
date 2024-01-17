<?php

namespace Modules\Sales\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Sales\Entities\SalesDocument;
use Modules\Sales\Entities\SalesDocumentType;
use Modules\Sales\Events\CreateSalesDocumentType;
use Modules\Sales\Events\DestroySalesDocumentType;
use Modules\Sales\Events\UpdateSalesDocumentType;

class SalesDocumentTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if (\Auth::user()->can('salesdocumenttype manage')) {
            $types = SalesDocumentType::where('created_by', creatorId())->where('workspace', getActiveWorkSpace())->get();
            return view('sales::document_type.index', compact('types'));
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
        if (\Auth::user()->can('salesdocumenttype create')) {
            return view('sales::document_type.create');
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
        if (\Auth::user()->can('salesdocumenttype create')) {
            $validator = \Validator::make(
                $request->all(),
                ['name' => 'required|max:40',]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $name                                   = $request['name'];
            $salesdocumenttype                      = new SalesDocumentType();
            $salesdocumenttype->name                = $name;
            $salesdocumenttype->workspace         = getActiveWorkSpace();
            $salesdocumenttype->created_by = creatorId();
            $salesdocumenttype->save();
            event(new CreateSalesDocumentType($request, $salesdocumenttype));

            return redirect()->route('salesdocument_type.index')->with('success', 'Document Type successfully created.');
        } else {
            return redirect()->back()->with('error', 'permission Denied');
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show()
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        if (\Auth::user()->can('salesdocumenttype edit')) {
            $salesdocumenttype = SalesDocumentType::find($id);
            return view('sales::document_type.edit', compact('salesdocumenttype'));
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
    public function update(Request $request, $id)
    {
        if (\Auth::user()->can('salesdocumenttype edit')) {
            $validator = \Validator::make(
                $request->all(),
                ['name' => 'required|max:40',]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            $salesdocumenttype = SalesDocumentType::find($id);
            $salesdocumenttype['name']       = $request->name;
            $salesdocumenttype['workspace']         = getActiveWorkSpace();
            $salesdocumenttype['created_by'] = creatorId();
            $salesdocumenttype->update();
            event(new UpdateSalesDocumentType($request, $salesdocumenttype));

            return redirect()->route('salesdocument_type.index')->with(
                'success',
                'Document Type successfully updated.'
            );
        } else {
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
        if (\Auth::user()->can('salesdocumenttype delete')) {
            $salesdocument = SalesDocument::where('type', '=', $id)->count();
            if ($salesdocument == 0) {
                $salesdocumenttype = SalesDocumentType::find($id);
                event(new DestroySalesDocumentType($salesdocumenttype));

                $salesdocumenttype->delete();

                return redirect()->route('salesdocument_type.index')->with(
                    'success',
                    'Document Type successfully deleted.'
                );
            } else {
                return redirect()->back()->with('error', 'This Document Type is Used on Sales Document.');
            }
        } else {
            return redirect()->back()->with('error', 'permission Denied');
        }
    }
}
