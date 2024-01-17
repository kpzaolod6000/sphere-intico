<?php

namespace Modules\Sales\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\User;
use Modules\Sales\Entities\SalesAccount;
use Modules\Sales\Entities\Opportunities;
use Modules\Sales\Entities\OpportunitiesStage;
use Modules\Sales\Entities\Contact;
use Modules\Sales\Entities\Stream;
use Modules\Sales\Entities\UserDefualtView;
use Modules\Sales\Entities\SalesUtility;
use Modules\Sales\Entities\SalesDocument;
use Illuminate\Support\Facades\Auth;
use Modules\Sales\Entities\Quote;
use Modules\Sales\Entities\SalesInvoice;
use Modules\Sales\Entities\SalesOrder;
use Modules\Sales\Events\CreateChangeOrder;
use Modules\Sales\Events\CreateOpportunities;
use Modules\Sales\Events\DestroyOpportunities;
use Modules\Sales\Events\UpdateOpportunities;

class OpportunitiesController extends Controller
{
     /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if(\Auth::user()->can('opportunities manage'))
        {
            $opportunitiess = Opportunities::where('workspace',getActiveWorkSpace())->get();

            $defualtView         = new UserDefualtView();
            $defualtView->route  = \Request::route()->getName();
            $defualtView->module = 'opportunities';
            $defualtView->view   = 'list';
            SalesUtility::userDefualtView($defualtView);
            return view('sales::opportunities.index', compact('opportunitiess'));
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
        if(\Auth::user()->can('opportunities create'))
        {
            $account_name        = SalesAccount::where('workspace',getActiveWorkSpace())->get()->pluck('name', 'id');
            $user                = User::where('workspace_id',getActiveWorkSpace())->emp()->get()->pluck('name', 'id');
            $user->prepend('--', 0);
            $opportunities_stage = OpportunitiesStage::where('created_by', creatorId())->where('workspace',getActiveWorkSpace())->get()->pluck('name', 'id');
            $leadsource = "";
            if(module_is_active('Lead')){
            $leadsource          = \Modules\Lead\Entities\Source::where('created_by',creatorId())->where('workspace_id',getActiveWorkSpace())->get()->pluck('name', 'id');
            }
            $contact             = Contact::where('created_by', creatorId())->where('workspace',getActiveWorkSpace())->get()->pluck('name', 'id');
            $contact->prepend('--', 0);

            return view('sales::opportunities.create', compact('user','opportunities_stage','account_name', 'leadsource' ,'contact', 'type', 'id'));
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
        if(\Auth::user()->can('opportunities create'))
        {
            $validator = \Validator::make(
                $request->all(), [
                                   'name' => 'required|max:120',
                                   'amount' => 'required|numeric',
                                   'probability' => 'required|numeric',
                                   'stage' => 'required',
                                   'close_date' => 'required',
                                   'account' => 'required',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $opportunities                = new Opportunities();
            $opportunities['user_id']     = $request->user;
            $opportunities['name']        = $request->name;
            $opportunities['account']     = $request->account;
            $opportunities['stage']       = $request->stage;
            $opportunities['amount']      = $request->amount;
            $opportunities['probability'] = $request->probability;
            $opportunities['close_date']  = $request->close_date;
            $opportunities['contact']     = $request->contact;
            $opportunities['lead_source'] = $request->lead_source;
            $opportunities['description'] = $request->description;
            $opportunities['workspace']         = getActiveWorkSpace();
            $opportunities['created_by']  = creatorId();
            $opportunities->save();

            Stream::create(
                [
                    'user_id' => Auth::user()->id,'created_by' => creatorId(),
                    'log_type' => 'created',
                    'remark' => json_encode(
                        [
                            'owner_name' => Auth::user()->username,
                            'title' => 'opportunities',
                            'stream_comment' => '',
                            'user_name' => $opportunities->name,
                        ]
                    ),
                ]
            );
            event(new CreateOpportunities($request,$opportunities));

            return redirect()->back()->with('success', __('Opportunities Successfully Created.'));
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
        if(\Auth::user()->can('opportunities show'))
        {
            $opportunities = Opportunities::find($id);
            $satge         = OpportunitiesStage::find($id);
            $account_name  = SalesAccount::find($id);


            return view('sales::opportunities.view', compact('opportunities','satge' ,'account_name'));
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
        if(\Auth::user()->can('opportunities edit'))
        {
            $opportunities = Opportunities::find($id);
            $stages        = OpportunitiesStage::where('created_by', creatorId())->where('workspace',getActiveWorkSpace())->get()->pluck('name', 'id');
            $lead_source = "";
            if(module_is_active('Lead')){
            $lead_source   = \Modules\Lead\Entities\Source::where('created_by',creatorId())->where('workspace_id',getActiveWorkSpace())->get()->pluck('name', 'id');
            }
            $user          = User::where('workspace_id',getActiveWorkSpace())->emp()->pluck('name', 'id');
            $user->prepend('--', 0);
            $account_name  = SalesAccount::where('created_by', creatorId())->where('workspace',getActiveWorkSpace())->get()->pluck('name', 'id');
            $contact       = Contact::where('created_by', creatorId())->where('workspace',getActiveWorkSpace())->get()->pluck('name', 'id');
            $contact->prepend('--', '');
            $documents     = SalesDocument::where('opportunities', $opportunities->id)->where('workspace',getActiveWorkSpace())->get();
            $parent        = 'opportunities';
            $log_type      = 'opportunities comment';
            $streams       = Stream::where('log_type', $log_type)->get();
            $salesorders   = SalesOrder::where('opportunity', $opportunities->id)->where('workspace',getActiveWorkSpace())->get();
            $quotes        = Quote::where('opportunity', $opportunities->id)->where('workspace',getActiveWorkSpace())->get();
            $salesinvoices = SalesInvoice::where('opportunity', $opportunities->id)->where('workspace',getActiveWorkSpace())->get();

            // get previous user id
            $previous = Opportunities::where('id', '<', $opportunities->id)->max('id');
            // get next user id
            $next = Opportunities::where('id', '>', $opportunities->id)->min('id');


            return view('sales::opportunities.edit', compact('opportunities','salesorders','quotes','salesinvoices', 'user','stages', 'lead_source','account_name', 'contact', 'documents', 'streams', 'previous', 'next'));
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
        if(\Auth::user()->can('opportunities edit'))
        {
            $opportunities = Opportunities::find($id);
            $validator     = \Validator::make(
                $request->all(), [
                                   'name' => 'required|max:120',
                                   'amount' => 'required|numeric',
                                   'probability' => 'required|numeric',

                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $opportunities['user_id']     = $request->user;
            $opportunities['name']        = $request->name;
            $opportunities['account']     = $request->account;
            $opportunities['contact']    = $request->contact;
            $opportunities['stage']       = $request->stage;
            $opportunities['amount']      = $request->amount;
            $opportunities['probability'] = $request->probability;
            $opportunities['close_date']  = $request->close_date;
            $opportunities['lead_source'] = $request->lead_source;
            $opportunities['description'] = $request->description;
            $opportunities['workspace']   = getActiveWorkSpace();
            $opportunities['created_by']  = creatorId();
            $opportunities->update();

            Stream::create(
                [
                    'user_id' => Auth::user()->id,'created_by' => creatorId(),
                    'log_type' => 'updated',
                    'remark' => json_encode(
                        [
                            'owner_name' => Auth::user()->username,
                            'title' => 'opportunities',
                            'stream_comment' => '',
                            'user_name' => $opportunities->name,
                        ]
                    ),
                ]
            );
            event(new UpdateOpportunities($request,$opportunities));

            return redirect()->back()->with('success', __('Opportunities Successfully Updated.'));
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
        if(\Auth::user()->can('opportunities delete'))
        {
            $opportunities = Opportunities::find($id);
            event(new DestroyOpportunities($opportunities));

            $opportunities->delete();

            return redirect()->back()->with('success', 'Opportunities ' . $opportunities->name . ' Deleted!');
        }
        else
        {
            return redirect()->back()->with('error', 'permission Denied');
        }
    }

    public function grid()
    {
        if(\Auth::user()->can('opportunities manage'))
        {
            $stages         = OpportunitiesStage::where('created_by', creatorId())->where('workspace',getActiveWorkSpace())->get();
            $opportunities = Opportunities::where('created_by', creatorId())->where('workspace',getActiveWorkSpace())->get();

            $defualtView         = new UserDefualtView();
            $defualtView->route  = \Request::route()->getName();
            $defualtView->module = 'opportunities';
            $defualtView->view   = 'kanban';
            SalesUtility::userDefualtView($defualtView);

            return view('sales::opportunities.grid', compact('opportunities', 'stages'));
        }
        else
        {
            return redirect()->back()->with('error', 'permission Denied');
        }
    }

    public function changeorder(Request $request)
    {
        $post          = $request->all();
        $opportunities = Opportunities::find($post['opo_id']);
        $stage         = OpportunitiesStage::find($post['stage_id']);


        if(!empty($stage))
        {
            $opportunities->stage = $post['stage_id'];
            $opportunities->save();
        }
        event(new CreateChangeOrder($request,$opportunities));

        foreach($post['order'] as $key => $item)
        {
            $order        = Opportunities::find($item);
            $order->stage = $post['stage_id'];
            $order->save();
        }
    }
}
