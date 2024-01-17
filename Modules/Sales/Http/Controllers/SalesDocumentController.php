<?php

namespace Modules\Sales\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\User;
use Modules\Sales\Entities\SalesDocument;
use Modules\Sales\Entities\UserDefualtView;
use Modules\Sales\Entities\SalesUtility;
use Modules\Sales\Entities\Opportunities;
use Modules\Sales\Entities\Stream;
use Modules\Sales\Entities\SalesDocumentFolder;
use Modules\Sales\Entities\SalesDocumentType;
use Modules\Sales\Entities\SalesAccount;
use Illuminate\Support\Facades\Auth;
use Modules\Sales\Events\CreateSalesDocument;
use Modules\Sales\Events\DestroySalesDocument;
use Modules\Sales\Events\UpdateSalesDocument;

class SalesDocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if(\Auth::user()->can('salesdocument manage'))
        {
            $documents = SalesDocument::where('created_by', creatorId())->where('workspace',getActiveWorkSpace())->get();
            $defualtView         = new UserDefualtView();
            $defualtView->route  = \Request::route()->getName();
            $defualtView->module = 'document';
            $defualtView->view   = 'list';
            SalesUtility::userDefualtView($defualtView);
            return view('sales::document.index', compact('documents'));
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
    public function create($type, $id)
    {
        if(\Auth::user()->can('salesdocument create'))
        {
            $status  = SalesDocument::$status;
            $user    = User::where('workspace_id',getActiveWorkSpace())->emp()->pluck('name', 'id');
            $user->prepend('--', 0);
            $folder  = SalesDocumentFolder::where('created_by', creatorId())->where('workspace',getActiveWorkSpace())->get()->pluck('name', 'id');
            $account = SalesAccount::where('created_by', creatorId())->where('workspace',getActiveWorkSpace())->get()->pluck('name', 'id');
            $account->prepend('--', 0);
            $types   = SalesDocumentType::where('created_by', creatorId())->where('workspace',getActiveWorkSpace())->get()->pluck('name', 'id');
            $types->prepend('none', '');
            $opportunities = Opportunities::where('created_by', creatorId())->where('workspace',getActiveWorkSpace())->get()->pluck('name', 'id');
            $opportunities->prepend('--', 0);

            return view('sales::document.create', compact('status', 'user','type', 'id', 'folder','types','account', 'opportunities'));
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
        if(\Auth::user()->can('salesdocument create'))
        {
            $validator = \Validator::make(
                $request->all(), [
                                   'name' => 'required|max:120',
                                   'folder' => 'required',
                                   'type' => 'required',
                                   'publish_date' => 'required',
                                   'expiration_date' => 'required',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            if(!empty($request->attachment))
            {
                $filenameWithExt = $request->file('attachment')->getClientOriginalName();
                $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension       = $request->file('attachment')->getClientOriginalExtension();
                $fileNameToStore = $filename . '_' . time() . '.' . $extension;
                $folder = SalesDocumentFolder::find($request->folder);
                $id = $folder->parent;
                if($folder->parent)
                {
                    $parent = SalesDocumentFolder::find($id);
                    $uplaod = upload_file($request,'attachment',$fileNameToStore,$parent->name.'/'.$folder->name);
                }
                else
                {
                    $uplaod = upload_file($request,'attachment',$fileNameToStore,$folder->name);
                }
                if($uplaod['flag'] == 1)
                {
                    $url = $uplaod['url'];
                }
                else
                {
                    return redirect()->back()->with('error',$uplaod['msg']);
                }
            }

            $salesdocument                    = new SalesDocument();
            $salesdocument['user_id']         = $request->user;
            $salesdocument['name']            = $request->name;
            $salesdocument['account']         = $request->account;
            $salesdocument['folder']          = $request->folder;
            $salesdocument['opportunities']   = $request->opportunities;
            $salesdocument['type']            = $request->type;
            $salesdocument['status']          = $request->status;
            $salesdocument['publish_date']    = $request->publish_date;
            $salesdocument['expiration_date'] = $request->expiration_date;
            $salesdocument['description']     = $request->description;
            $salesdocument['attachment']      = !empty($request->attachment) ? $url : '';
            $salesdocument['workspace']       = getActiveWorkSpace();
            $salesdocument['created_by']      = creatorId();
            $salesdocument->save();

            Stream::create(
                [
                    'user_id' => Auth::user()->id,'created_by' => creatorId(),
                    'log_type' => 'created',
                    'remark' => json_encode(
                        [
                            'owner_name' => Auth::user()->username,
                            'title' => 'document',
                            'stream_comment' => '',
                            'user_name' => $salesdocument->name,
                        ]
                    ),
                ]
            );
            event(new CreateSalesDocument($request,$salesdocument));

            return redirect()->back()->with('success', __('Document Successfully Created.'));
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
        if(\Auth::user()->can('salesdocument show'))
        {
            $salesdocument = SalesDocument ::find($id);
            return view('sales::document.view', compact('salesdocument'));
        }
        else
        {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        if(\Auth::user()->can('salesdocument edit'))
        {
            $salesdocument = SalesDocument ::find($id);
            $folders = SalesDocumentFolder::where('created_by', creatorId())->where('workspace',getActiveWorkSpace())->get()->pluck('name', 'id');
            $type    = SalesDocumentType::where('created_by', creatorId())->where('workspace',getActiveWorkSpace())->get()->pluck('name', 'id');
            $type->prepend('none', '');
            $status        = SalesDocument::$status;
            $user          = User::where('workspace_id',getActiveWorkSpace())->emp()->get()->pluck('name', 'id');
            $user->prepend('--', 0);
            $accounts      = SalesAccount::where('document_id',$salesdocument->id)->where('workspace',getActiveWorkSpace())->get();
            $opportunities = Opportunities::where('created_by', creatorId())->where('workspace',getActiveWorkSpace())->get()->pluck('name', 'id');
            $opportunities->prepend('--', '');
            $account_name = SalesAccount::where('created_by', creatorId())->where('workspace',getActiveWorkSpace())->get()->pluck('name', 'id');
            $account_name->prepend('--', 0);

            // get previous user id
            $previous = SalesDocument::where('id', '<', $salesdocument->id)->max('id');
            // get next user id
            $next = SalesDocument::where('id', '>', $salesdocument->id)->min('id');

            return view('sales::document.edit', compact('salesdocument','status', 'user', 'accounts','folders', 'type','opportunities','account_name','previous','next'));
        }
        else
        {
            return redirect()->back()->with('error', 'permission Denied');
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
        if(\Auth::user()->can('salesdocument edit'))
        {
            $validator = \Validator::make(
                $request->all(), [
                                   'name' => 'required|max:120',

                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
           }
           $salesdocument = SalesDocument ::find($id);
            if(!empty($request->attachment))
            {
                $filenameWithExt = $request->file('attachment')->getClientOriginalName();
                $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension       = $request->file('attachment')->getClientOriginalExtension();
                $fileNameToStore = $filename . '_' . time() . '.' . $extension;

                $folder = SalesDocumentFolder::find($request->folder);
                $id = $folder->parent;
                if($folder->parent)
                {
                    $parent = SalesDocumentFolder::find($id);
                    $uplaod = upload_file($request,'attachment',$fileNameToStore,$parent->name.'/'.$folder->name);
                }
                else
                {
                    $uplaod = upload_file($request,'attachment',$fileNameToStore,$folder->name);
                }
                if($uplaod['flag'] == 1)
                {
                    $url = $uplaod['url'];
                }
                else
                {
                    return redirect()->back()->with('error',$uplaod['msg']);
                }
            }

            $salesdocument['user_id']         = $request->user;
            $salesdocument['name']            = $request->name;
            $salesdocument['account']         = $request->account;
            $salesdocument['folder']          = $request->folder;
            $salesdocument['type']            = $request->type;
            $salesdocument['opportunities']   = $request->opportunities;
            $salesdocument['status']          = $request->status;
            $salesdocument['publish_date']    = $request->publish_date;
            $salesdocument['expiration_date'] = $request->expiration_date;
            $salesdocument['description']     = $request->description;
            if(!empty($request->attachment))
            {
                $salesdocument['attachment'] = $url;
            }
            $salesdocument['workspace']       = getActiveWorkSpace();
            $salesdocument['created_by']      = creatorId();
            $salesdocument->update();

            Stream::create(
                [
                    'user_id' => Auth::user()->id,'created_by' => creatorId(),
                    'log_type' => 'updated',
                    'remark' => json_encode(
                        [
                            'owner_name' => Auth::user()->username,
                            'title' => 'document',
                            'stream_comment' => '',
                            'user_name' => $salesdocument->name,
                        ]
                    ),
                ]
            );
            event(new UpdateSalesDocument($request,$salesdocument));

            return redirect()->back()->with('success', __('Document Successfully Updated.'));
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
        if(\Auth::user()->can('salesdocument delete'))
        {
            $salesdocument = SalesDocument ::find($id);
            if(!empty($salesdocument->attachment))
            {
                delete_file($salesdocument->attachment);
            }
            event(new DestroySalesDocument($salesdocument));

            $salesdocument->delete();

            return redirect()->back()->with('success', __('Document Successfully Deleted.'));
        }
        else
        {
            return redirect()->back()->with('error', 'permission Denied');
        }
    }

    public function grid()
    {
        if(\Auth::user()->can('salesdocument manage'))
        {
            $documents = SalesDocument::where('created_by', creatorId())->where('workspace',getActiveWorkSpace())->get();

            $defualtView         = new UserDefualtView();
            $defualtView->route  = \Request::route()->getName();
            $defualtView->module = 'document';
            $defualtView->view   = 'list';
            SalesUtility::userDefualtView($defualtView);
            return view('sales::document.grid', compact('documents'));
        }
        else
        {
            return redirect()->back()->with('error', 'permission Denied');
        }
    }
}
