<?php

namespace Modules\Sales\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\User;
use Modules\Sales\Entities\CommonCase;
use Modules\Sales\Entities\CaseType;
use Modules\Sales\Entities\UserDefualtView;
use Modules\Sales\Entities\SalesUtility;
use Modules\Sales\Entities\Contact;
use Modules\Sales\Entities\SalesAccount;
use Modules\Sales\Entities\Stream;
use Illuminate\Support\Facades\Auth;
use Modules\Sales\Events\CreateCommonCase;
use Modules\Sales\Events\DestroyCommonCase;
use Modules\Sales\Events\UpdateCommonCase;

class CommonCaseController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if(\Auth::user()->can('case manage'))
        {
            $cases = CommonCase::where('created_by', creatorId())->where('workspace',getActiveWorkSpace())->get();

            $defualtView         = new UserDefualtView();
            $defualtView->route  = \Request::route()->getName();
            $defualtView->module = 'commoncases';
            $defualtView->view   = 'list';
            SalesUtility::userDefualtView($defualtView);

            return view('sales::commoncase.index', compact('cases'));
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
        if(\Auth::user()->can('case create'))
        {
            $status       = CommonCase::$status;
            $account      = SalesAccount::where('created_by', creatorId())->where('workspace',getActiveWorkSpace())->get()->pluck('name', 'id');
            $account->prepend('--', 0);
            $contact_name = Contact::where('created_by', creatorId())->where('workspace',getActiveWorkSpace())->get()->pluck('name', 'id');
            $contact_name->prepend('--', 0);
            $case_type    = CaseType::where('created_by', creatorId())->where('workspace',getActiveWorkSpace())->get()->pluck('name', 'id');
            $priority     = CommonCase::$priority;
            $user         = User::where('workspace_id',getActiveWorkSpace())->emp()->get()->pluck('name', 'id');
            $user->prepend('--', 0);
            return view('sales::commoncase.create', compact('status', 'account', 'user', 'case_type', 'priority', 'contact_name', 'type', 'id'));
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
        if(\Auth::user()->can('case create'))
        {
            $validator = \Validator::make(
                $request->all(), [
                                   'name' => 'required|max:120',
                                   'image' => 'image',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            if(!empty($request->attachments))
            {
                $filenameWithExt = $request->file('attachments')->getClientOriginalName();
                $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension       = $request->file('attachments')->getClientOriginalExtension();
                $fileNameToStore = $filename . '_' . time() . '.' . $extension;

                $uplaod = upload_file($request,'attachments',$fileNameToStore,'Cases');
                if($uplaod['flag'] == 1)
                {
                    $url = $uplaod['url'];
                }
                else
                {
                    return redirect()->back()->with('error',$uplaod['msg']);
                }
            }

            $commoncase                = new CommonCase();
            $commoncase['user_id']     = $request->user;
            $commoncase['name']        = $request->name;
            $commoncase['number']      = $this->caseNumber();
            $commoncase['status']      = $request->status;
            $commoncase['account']     = $request->account;
            $commoncase['priority']    = $request->priority;
            $commoncase['contact']    = $request->contact;
            $commoncase['type']        = $request->type;
            $commoncase['description'] = $request->description;
            $commoncase['attachments'] = !empty($request->attachments) ? $url : '';
            $commoncase['workspace']         = getActiveWorkSpace();
            $commoncase['created_by']  = creatorId();
            $commoncase->save();
            Stream::create(
                [
                    'user_id' => Auth::user()->id,'created_by' => creatorId(),
                    'log_type' => 'created',
                    'remark' => json_encode(
                        [
                            'owner_name' => Auth::user()->username,
                            'title' => 'commoncase',
                            'stream_comment' => '',
                            'user_name' => $commoncase->name,
                        ]
                    ),
                ]
            );
            event(new CreateCommonCase($request,$commoncase));

            return redirect()->back()->with('success', __('common case Successfully Created.'));
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
        if(\Auth::user()->can('case show'))
        {
            $commonCase = CommonCase::find($id);

            return view('sales::commoncase.view', compact('commonCase'));
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
        if(\Auth::user()->can('case edit'))
        {
            $commonCase = CommonCase::find($id);
            $status     = CommonCase::$status;
            $account    = SalesAccount::where('created_by', creatorId())->where('workspace',getActiveWorkSpace())->get()->pluck('name', 'id');
            $account->prepend('--', 0);
            $contact   = Contact::where('created_by', creatorId())->where('workspace',getActiveWorkSpace())->get()->pluck('name', 'id');
            $contact->prepend('--', 0);
            $type       = CaseType::where('created_by', creatorId())->where('workspace',getActiveWorkSpace())->get()->pluck('name', 'id');
            $priority   = CommonCase::$priority;
            $user       = User::where('workspace_id',getActiveWorkSpace())->emp()->pluck('name', 'id');
            $user->prepend('--', 0);
            // get previous user id
            $previous = CommonCase::where('id', '<', $commonCase->id)->max('id');
            // get next user id
            $next = CommonCase::where('id', '>', $commonCase->id)->min('id');
            $log_type = 'commoncases comment';
            $streams  = Stream::where('log_type', $log_type)->get();

            return view('sales::commoncase.edit', compact('commonCase', 'status', 'user', 'priority', 'type', 'contact', 'account', 'streams','previous','next'));
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
    public function update(Request $request, $id)
    {
        if(\Auth::user()->can('case edit'))
        {
            $validator  = \Validator::make(
                $request->all(), [
                                   'name' => 'required|max:120',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            $commonCase = CommonCase::find($id);
            if(!empty($request->attachments))
            {
                $filenameWithExt = $request->file('attachments')->getClientOriginalName();
                $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension       = $request->file('attachments')->getClientOriginalExtension();
                $fileNameToStore = $filename . '_' . time() . '.' . $extension;

                $uplaod = upload_file($request,'attachments',$fileNameToStore,'Cases');
                if($uplaod['flag'] == 1)
                {
                    $url = $uplaod['url'];
                }
                else
                {
                    return redirect()->back()->with('error',$uplaod['msg']);
                }
            }

            $commonCase['user_id']     = $request->user;
            $commonCase['name']        = $request->name;
            $commonCase['status']      = $request->status;
            $commonCase['account']     = $request->account;
            $commonCase['priority']    = $request->priority;
            $commonCase['contact']     = $request->contact;
            $commonCase['type']        = $request->type;
            $commonCase['description'] = $request->description;
            $commonCase['attachments'] = !empty($request->attachments) ? $url : '';
            $commoncase['workspace']   = getActiveWorkSpace();
            $commonCase['created_by']  = creatorId();
            $commonCase->update();

            Stream::create(
                [
                    'user_id' => Auth::user()->id,'created_by' => creatorId(),
                    'log_type' => 'updated',
                    'remark' => json_encode(
                        [
                            'owner_name' => Auth::user()->username,
                            'title' => 'commonCase',
                            'stream_comment' => '',
                            'user_name' => $commonCase->name,
                        ]
                    ),
                ]
            );
            event(new UpdateCommonCase($request,$commonCase));

            return redirect()->back()->with('success', __('Cases Successfully updated.'));
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
        if(\Auth::user()->can('case delete'))
        {
            $commonCase = CommonCase::find($id);
            if(!empty($commonCase->attachments))
            {
                delete_file($commonCase->attachments);
            }

            event(new DestroyCommonCase($commonCase));
            $commonCase->delete();

            return redirect()->back()->with('success', 'Common Case ' . $commonCase->name . ' Deleted!');
        }
        else
        {
            return redirect()->back()->with('error', 'permission Denied');
        }
    }

    public function grid()
    {
        if(\Auth::user()->can('case manage'))
        {
            $commonCases = CommonCase::where('created_by', creatorId())->where('workspace',getActiveWorkSpace())->get();

            $defualtView         = new UserDefualtView();
            $defualtView->route  = \Request::route()->getName();
            $defualtView->module = 'commoncases';
            $defualtView->view   = 'grid';
            SalesUtility::userDefualtView($defualtView);

            return view('sales::commoncase.grid', compact('commonCases'));
        }
        else
        {
            return redirect()->back()->with('error', 'permission Denied');
        }
    }

    function caseNumber()
    {
        $latest = CommonCase::where('workspace',getActiveWorkSpace())->latest()->first();
        if(!$latest)
        {
            return 1;
        }

        return $latest->number + 1;
    }
}
