<?php

namespace Modules\Performance\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Performance\Entities\Competencies;
use Modules\Performance\Entities\Performance_Type;
use Illuminate\Support\Facades\Auth;

class CompetenciesController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if (\Auth::user()->can('competencies manage')) {
            $competencies = Competencies::where('created_by', creatorId())->where('workspace',getActiveWorkSpace())->get();
            return view('performance::competencies.index', compact('competencies'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        if (\Auth::user()->can('competencies manage'))
        {
            $user = \Auth::user();
            $performance_types = Performance_Type::where('created_by', '=', creatorId())->where('workspace',getActiveWorkSpace())->get()->pluck('name', 'id');

            return view('performance::competencies.create', compact('performance_types'));
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
        if (\Auth::user()->can('competencies create')) {

            $validator = \Validator::make(
                $request->all(),
                [
                    'name' => 'required',
                    'type' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $competencies             = new Competencies();
            $competencies->name       = $request->name;
            $competencies->type       = $request->type;
            $competencies->workspace  = getActiveWorkSpace();
            $competencies->created_by = creatorId();
            $competencies->save();

            return redirect()->route('competencies.index')->with('success', __('Competencies  successfully created.'));
        } else {
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
        if (\Auth::user()->can('competencies edit'))
        {
            $competencies = Competencies::find($id);
            $types = Performance_Type::where('created_by', '=', creatorId())->where('workspace',getActiveWorkSpace())->get()->pluck('name', 'id');

            return view('performance::competencies.edit', compact('types', 'competencies'));
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
        if (\Auth::user()->can('competencies edit')) {

            $validator = \Validator::make(
                $request->all(),
                [
                    'name' => 'required',
                    'type' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            $competencies       = Competencies::find($id);
            $competencies->name = $request->name;
            $competencies->type = $request->type;
            $competencies->save();

            return redirect()->route('competencies.index')->with('success', __('Competencies  successfully updated.'));
        } else {
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
        if (\Auth::user()->can('competencies delete')) {
            $competencies = Competencies::find($id);
            if($competencies->created_by == creatorId() &&  $competencies->workspace  == getActiveWorkSpace())
            {
            $competencies->delete();
            return redirect()->route('competencies.index')->with('success', __('Competencies  successfully deleted.'));
            }
            else
            {
                return redirect()->back()->with('error', __('Permission denied.'));
            }

        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
