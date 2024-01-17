<?php

namespace Modules\Sales\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Sales\Entities\Opportunities;
use Modules\Sales\Entities\OpportunitiesStage;
use Modules\Sales\Events\CreateOpportunitiesstage;
use Modules\Sales\Events\DestroyOpportunitiesstage;
use Modules\Sales\Events\UpdateOpportunitiesstage;

class OpportunitiesStageController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if(\Auth::user()->can('opportunitiesstage manage'))
        {
            $opportunities_stages = OpportunitiesStage::where('created_by', creatorId())->where('workspace',getActiveWorkSpace())->get();

            return view('sales::opportunities_stage.index', compact('opportunities_stages'));
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
        if(\Auth::user()->can('opportunitiesstage create'))
        {

            return view('sales::opportunities_stage.create');
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
        if(\Auth::user()->can('opportunitiesstage create'))
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
            $name                             = $request['name'];
            $opportunitiesstage               = new OpportunitiesStage();
            $opportunitiesstage->name         = $name;
            $opportunitiesstage->workspace  = getActiveWorkSpace();
            $opportunitiesstage['created_by'] = creatorId();
            $opportunitiesstage->save();
            event(new CreateOpportunitiesstage($request,$opportunitiesstage));

            return redirect()->back()->with('success', 'Opportunities Stage successfully created.');
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
    public function edit(OpportunitiesStage $opportunitiesStage)
    {
        if(\Auth::user()->can('opportunitiesstage edit'))
        {
            return view('sales::opportunities_stage.edit', compact('opportunitiesStage'));
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
    public function update(Request $request,OpportunitiesStage $opportunitiesStage)
    {
        if(\Auth::user()->can('opportunitiesstage edit'))
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
            $name                             = $request['name'];
            $opportunitiesStage->name         = $name;
            $opportunitiesstage['workspace']         = getActiveWorkSpace();
            $opportunitiesStage['created_by'] = creatorId();
            $opportunitiesStage->save();
            event(new UpdateOpportunitiesstage($request,$opportunitiesStage));

            return redirect()->route('opportunities_stage.index')->with('success', 'Opportunities Stage successfully updated.');
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
    public function destroy(OpportunitiesStage $opportunitiesStage)
    {
        if(\Auth::user()->can('opportunitiesstage delete'))
        {
            $opportunities = Opportunities::where('stage', '=', $opportunitiesStage->id)->count();
            if($opportunities==0){
                event(new DestroyOpportunitiesstage($opportunitiesStage));

                $opportunitiesStage->delete();

                return redirect()->route('opportunities_stage.index')->with('success', 'Opportunities Stage successfully deleted.');
            }
            else{
                return redirect()->back()->with('error', 'Please remove Opportunities from stage:'.$opportunitiesStage->name);
            }
        }
        else
        {
            return redirect()->back()->with('error', 'permission Denied');
        }
    }
}
