<?php

namespace Modules\Sales\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Sales\Entities\CaseType;
use Modules\Sales\Entities\CommonCase;
use Modules\Sales\Events\CreateCaseType;
use Modules\Sales\Events\DestroyCaseType;
use Modules\Sales\Events\UpdateCaseType;
use PhpParser\Node\Stmt\Case_;

class CaseTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if(\Auth::user()->can('casetype manage'))
        {
            $types = CaseType::where('created_by', creatorId())->where('workspace',getActiveWorkSpace())->get();

            return view('sales::case_type.index', compact('types'));
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
        if(\Auth::user()->can('casetype create'))
        {
            return view('sales::case_type.create');
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
        if(\Auth::user()->can('casetype create'))
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
            $name                   = $request['name'];
            $casetype               = new CaseType();
            $casetype->name         = $name;
            $casetype['workspace']  = getActiveWorkSpace();
            $casetype['created_by'] = creatorId();
            $casetype->save();
            event(new CreateCaseType($request,$casetype));

            return redirect()->route('case_type.index')->with('success', 'Case Type successfully created.');
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
    public function edit(CaseType $caseType)
    {
        if(\Auth::user()->can('casetype edit'))
        {
            return view('sales::case_type.edit', compact('caseType'));
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
    public function update(Request $request,CaseType $caseType)
    {
        if(\Auth::user()->can('casetype edit'))
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
            $caseType['name'] = $request->name;
            $caseType['workspace']  = getActiveWorkSpace();
            $caseType['created_by']  = creatorId();
            $caseType->update();
            event(new UpdateCaseType($request,$caseType));

            return redirect()->route('case_type.index')->with(
                'success', 'Case Type successfully updated.'
            );
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
    public function destroy(CaseType $caseType)
    {
        if(\Auth::user()->can('casetype delete'))
        {
            $commoncase = CommonCase::where('type', '=', $caseType->id)->count();
            if($commoncase==0){
                event(new DestroyCaseType($caseType));

                $caseType->delete();

                return redirect()->route('case_type.index')->with(
                    'success', 'Case Type successfully deleted.'
                );
            }
            else{
                return redirect()->back()->with('error', 'This Cases Type is Used on Cases.');
            }
        }
        else
        {
            return redirect()->back()->with('error', 'permission Denied');
        }
    }
}
