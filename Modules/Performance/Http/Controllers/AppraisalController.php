<?php

namespace Modules\Performance\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Hrm\Entities\Branch;
use Modules\Hrm\Entities\Department;
use Modules\Hrm\Entities\Designation;
use Modules\Hrm\Entities\Employee;
use Modules\Performance\Entities\Competencies;
use Modules\Performance\Entities\Appraisal;
use Modules\Performance\Entities\Performance_Type;
use Modules\Performance\Entities\Indicator;
use Illuminate\Support\Facades\Auth;
use Modules\Performance\Events\CreateAppraisal;
use Modules\Performance\Events\DestroyAppraisal;
use Modules\Performance\Events\UpdateAppraisal;

class AppraisalController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if(\Auth::user()->can('appraisal manage'))
        {
            $user = \Auth::user();
            if(!in_array(Auth::user()->type, Auth::user()->not_emp_type))
            {
                $employee   = Employee::where('user_id', $user->id)->first();
                $competencyCount = Competencies::where('created_by', '=', $employee->created_by)->where('workspace',getActiveWorkSpace())->count();
                $appraisals = Appraisal::where('created_by', '=', creatorId())->where('workspace',getActiveWorkSpace())->where('branch', $employee->branch_id)->where('employee', $employee->id)->get();
            }
            else
            {
                $competencyCount = Competencies::where('created_by', '=', $user->id)->where('workspace',getActiveWorkSpace())->count();
                $appraisals = Appraisal::where('created_by', '=', creatorId())->where('workspace',getActiveWorkSpace())->get();

            }

            return view('performance::appraisal.index', compact('appraisals','competencyCount'));
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
        if(\Auth::user()->can('appraisal create'))
        {

            $brances = Branch::where('created_by', '=', creatorId())->where('workspace',getActiveWorkSpace())->get();

            $employee = Employee::where('created_by', creatorId())->where('workspace',getActiveWorkSpace())->get()->pluck('name','id');
            $employee->prepend('Select Employee', '');
            $performance_types = Performance_Type::where('created_by', '=', creatorId())->where('workspace',getActiveWorkSpace())->get();

            return view('performance::appraisal.create', compact('employee', 'brances', 'performance_types'));
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
        if(\Auth::user()->can('appraisal create'))
        {
            $validator = \Validator::make(
                $request->all(), [
                                   'brances' => 'required',
                                   'employee' => 'required',
                                   'rating'=> 'required',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $appraisal                 = new Appraisal();
            $employee = Employee::where('id', '=', $request->employee)->first();
            if(!empty($employee))
            {
                $appraisal->user_id = $employee->user_id;
            }
            $appraisal->branch         = $request->brances;
            $appraisal->employee       = $request->employee;
            $appraisal->appraisal_date = $request->appraisal_date;
            $appraisal->rating         = json_encode($request->rating, true);
            $appraisal->workspace      = getActiveWorkSpace();
            $appraisal->remark         = $request->remark;
            $appraisal->created_by     = creatorId();

            $appraisal->save();
            event(new CreateAppraisal($request, $appraisal));
            return redirect()->route('appraisal.index')->with('success', __('Appraisal successfully created.'));

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
    public function show(Appraisal $appraisal)
    {
        if(\Auth::user()->can('appraisal show'))
        {
            $rating = json_decode($appraisal->rating, true);
            $performance_types = Performance_Type::where('created_by', '=', creatorId())->where('workspace',getActiveWorkSpace())->get();
            $employee = Employee::find($appraisal->employee);
            $indicator = Indicator::where('branch',$employee->branch_id)->where('department',$employee->department_id)->where('designation',$employee->designation_id)->first();

            $ratings = json_decode($indicator->rating, true);

            return view('performance::appraisal.show', compact('appraisal', 'performance_types', 'rating','ratings'));
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
    public function edit(Appraisal $appraisal)
    {
        if(\Auth::user()->can('appraisal edit'))
        {
            $performance_types = Performance_Type::where('created_by', '=', creatorId())->where('workspace',getActiveWorkSpace())->get();

            $employee   = Employee::where('created_by', creatorId())->where('workspace',getActiveWorkSpace())->get()->pluck('name','id');
            $employee->prepend('Select Employee', '');

            $brances = Branch::where('created_by', '=', creatorId())->where('workspace',getActiveWorkSpace())->get();
            $rating = json_decode($appraisal->rating,true);

            return view('performance::appraisal.edit', compact('brances', 'employee', 'appraisal', 'performance_types','rating'));
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
    public function update(Request $request, Appraisal $appraisal)
    {
        if(\Auth::user()->can('appraisal edit'))
        {
            $validator = \Validator::make(
                $request->all(), [
                                   'brances' => 'required',
                                   'employees' => 'required',
                                   'rating'=> 'required',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $appraisal->branch         = $request->brances;
            $appraisal->employee       = $request->employees;
            $appraisal->appraisal_date = $request->appraisal_date;
            $appraisal->rating         = json_encode($request->rating, true);
            $appraisal->remark         = $request->remark;
            $appraisal->save();
            event(new UpdateAppraisal($request, $appraisal));

            return redirect()->route('appraisal.index')->with('success', __('Appraisal successfully updated.'));
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
    public function destroy(Appraisal $appraisal)
    {
        if(\Auth::user()->can('appraisal delete'))
        {
            if($appraisal->created_by == creatorId() &&  $appraisal->workspace  == getActiveWorkSpace())
            {
            event(new DestroyAppraisal($appraisal));

                $appraisal->delete();

                return redirect()->route('appraisal.index')->with('success', __('Appraisal successfully deleted.'));
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
    public function empByStar(Request $request)
    {
        $employee = Employee::find($request->employee);

        $indicator = Indicator::where('branch',$employee->branch_id)->where('department',$employee->department_id)->where('designation',$employee->designation_id)->first();

        $ratings = json_decode($indicator->rating, true);

        $performance_types = Performance_Type::where('created_by', '=', creatorId())->where('workspace',getActiveWorkSpace())->get();

        $viewRender = view('performance::appraisal.star', compact('ratings','performance_types'))->render();
        return response()->json(array('success' => true, 'html'=>$viewRender));

    }
    public function empByStar1(Request $request)
    {
        $employee = Employee::find($request->employee);

        $appraisal = Appraisal::find($request->appraisal);

        $indicator = Indicator::where('branch',$employee->branch_id)->where('department',$employee->department_id)->where('designation',$employee->designation_id)->first();

        $ratings = json_decode($indicator->rating, true);
        $rating = json_decode($appraisal->rating,true);
        $performance_types = Performance_Type::where('created_by', '=', creatorId())->where('workspace',getActiveWorkSpace())->get();
        $viewRender = view('performance::appraisal.staredit', compact('ratings','rating','performance_types'))->render();
        return response()->json(array('success' => true, 'html'=>$viewRender));

    }
    public function getemployee(Request $request)
    {
        $data['employee'] = Employee::where('branch_id',$request->branch_id)->get();
        return response()->json($data);


    }
}
