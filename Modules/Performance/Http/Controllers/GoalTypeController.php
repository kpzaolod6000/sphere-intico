<?php

namespace Modules\Performance\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Performance\Entities\GoalTracking;
use Modules\Performance\Entities\GoalType;

class GoalTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if(\Auth::user()->can('goal type manage'))
        {
            $goaltypes = GoalType::where('created_by', '=', creatorId())->where('workspace',getActiveWorkSpace())->get();

            return view('performance::goaltype.index', compact('goaltypes'));
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
        if(\Auth::user()->can('goal type create'))
        {
            return view('performance::goaltype.create');
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
        if(\Auth::user()->can('goal type create'))
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

            $goaltype                   = new GoalType();
            $goaltype->name             = $request->name;
            $goaltype->workspace        = getActiveWorkSpace();
            $goaltype->created_by       =creatorId();
            $goaltype->save();

            return redirect()->route('goaltype.index')->with('success', __('GoalType  successfully created.'));
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
    public function show($id)
    {
        return redirect()->back();
        return view('performance::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        if(\Auth::user()->can('goal type edit'))
        {
            $goalType = GoalType::find($id);

            return view('performance::goaltype.edit', compact('goalType'));
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
    public function update(Request $request, $id)
    {
        if(\Auth::user()->can('goal type edit'))
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
            $goalType       = GoalType::find($id);
            $goalType->name = $request->name;
            $goalType->save();

            return redirect()->route('goaltype.index')->with('success', __('GoalType  successfully updated.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        if(\Auth::user()->can('goal type delete'))
        {
            $goalType = GoalType::find($id);
            if($goalType->created_by == creatorId() && $goalType->workspace  == getActiveWorkSpace())
            {
                $goalTrackings = GoalTracking::where('goal_type', $goalType->id)->where('workspace',getActiveWorkSpace())->get();
                if(count($goalTrackings) == 0)
                {

                    $goalType->delete();
                }else{

                    return redirect()->route('goaltype.index')->with('error', __('This GoalType has Goal. Please remove the Goal from this GoalType.'));

                }

                return redirect()->route('goaltype.index')->with('success', __('GoalType successfully deleted.'));
            }
            else
            {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
