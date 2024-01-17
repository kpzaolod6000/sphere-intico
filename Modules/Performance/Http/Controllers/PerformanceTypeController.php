<?php

namespace Modules\Performance\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Performance\Entities\Performance_Type;
use Illuminate\Support\Facades\Auth;
use Modules\Performance\Entities\Competencies;

class PerformanceTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if(\Auth::user()->can('performancetype manage'))
        {
        $performance_types = Performance_Type::where('created_by', '=', creatorId())->where('workspace',getActiveWorkSpace())->get();
        return view('performance::performance_type.index', compact('performance_types'));
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
        if(\Auth::user()->can('performancetype create'))
        {
        return view('performance::performance_type.create');
        }
        else{
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
        if(\Auth::user()->can('performancetype create'))
        {
        $validator = \Validator::make(
            $request->all(),
            [
                'name' => 'required',
            ]
        );

        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first());
        }

        $performance_type               = new Performance_Type();
        $performance_type->name         = $request->name;
        $performance_type->workspace    = getActiveWorkSpace();
        $performance_type->created_by   = creatorId();
        $performance_type->save();

        return redirect()->back()->with('success', 'performancetype created successfully');
        }
        else{
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
        if(\Auth::user()->can('performancetype edit'))
        {
            $performance_type  = Performance_Type::find($id);
            return view('performance::performance_type.edit', compact('performance_type'));
        }else{
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
        if(\Auth::user()->can('performancetype edit'))
        {
            $validator = \Validator::make(
                $request->all(),
                [
                    'name' => 'required',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }

            $performance_type       = Performance_Type::findOrFail($id);
            $performance_type->name = $request->name;
            $performance_type->save();

        return redirect()->back()->with('success', 'Performance Type updated successfully');
        }
        else{
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
        if(\Auth::user()->can('performancetype delete'))
        {
            $performance_Type = Performance_Type::findOrFail($id);
            if($performance_Type->created_by == creatorId() &&  $performance_Type->workspace  == getActiveWorkSpace())
            {
                $competencies = Competencies::where('type', $performance_Type->id)->where('workspace',getActiveWorkSpace())->get();
                if(count($competencies) == 0){

                    $performance_Type->delete();
                }else{
                    return redirect()->route('performanceType.index')->with('error', __('This Performance Type has Competencies. Please remove the Competencies from this Performance Type.'));

                }

                return redirect()->route('performanceType.index')->with('success', __('Performance Type successfully deleted.'));
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
