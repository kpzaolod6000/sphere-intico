<?php

namespace Modules\Sales\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Sales\Entities\SalesDocument;
use Modules\Sales\Entities\SalesDocumentFolder;
use Modules\Sales\Events\CreateDocumentFolder;
use Modules\Sales\Events\DestroyDocumentFolder;
use Modules\Sales\Events\UpdateDocumentFolder;

class SalesDocumentFolderController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if (\Auth::user()->can('documentfolder manage')) {
            $folders = SalesDocumentFolder::where('created_by', creatorId())->where('workspace', getActiveWorkSpace())->get();

            return view('sales::document_folder.index', compact('folders'));
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
        if (\Auth::user()->can('documentfolder create')) {
            $parent = SalesDocumentFolder::where('created_by', creatorId())->where('workspace', getActiveWorkSpace())->get()->pluck('name', 'id');
            $parent->prepend('select parent', '');

            return view('sales::document_folder.create', compact('parent'));
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
        if (\Auth::user()->can('documentfolder create')) {
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

            $documentfolder               = new SalesDocumentFolder();
            $documentfolder->name         = $request['name'];
            $documentfolder->parent       = $request['parent'];
            $documentfolder->description  = $request['description'];
            $documentfolder->workspace    = getActiveWorkSpace();
            $documentfolder->created_by   = creatorId();
            $documentfolder->save();
            event(new CreateDocumentFolder($request, $documentfolder));

            return redirect()->route('salesdocument_folder.index')->with('success', 'Document Folders successfully created.');
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
    public function edit($id)
    {
        if (\Auth::user()->can('documentfolder edit')) {
            $documentFolder = SalesDocumentFolder::find($id);
            $parent = SalesDocumentFolder::where('created_by', creatorId())->where('workspace', getActiveWorkSpace())->get()->pluck('name', 'id');
            $parent->prepend('select parent', '');

            return view('sales::document_folder.edit', compact('documentFolder', 'parent'));
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
        if (\Auth::user()->can('documentfolder edit')) {
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
            $documentFolder = SalesDocumentFolder::find($id);
            $documentFolder->name         = $request['name'];
            $documentFolder->parent       = $request['parent'];
            $documentFolder->description  = $request['description'];
            $documentFolder->workspace    = getActiveWorkSpace();
            $documentFolder->created_by   = creatorId();
            $documentFolder->save();
            event(new UpdateDocumentFolder($request, $documentFolder));

            return redirect()->route('salesdocument_folder.index')->with('success', 'Document Folders successfully updated.');
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
        if (\Auth::user()->can('documentfolder delete')) {
            $salesdocument = SalesDocument::where('folder', '=', $id)->count();
            if ($salesdocument == 0) {
                $documentFolder = SalesDocumentFolder::find($id);
                event(new DestroyDocumentFolder($documentFolder));

                $documentFolder->delete();

                return redirect()->route('salesdocument_folder.index')->with('success', 'Document Folders successfully deleted.');
            } else {
                return redirect()->back()->with('error', 'This Document Folder is Used on Sales Document.');
            }
        } else {
            return redirect()->back()->with('error', 'permission Denied');
        }
    }
}
