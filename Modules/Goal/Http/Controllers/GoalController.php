<?php

namespace Modules\Goal\Http\Controllers;

use Modules\Goal\Entities\Goal;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Goal\Events\CreateFinacialGoal;
use Modules\Goal\Events\DestroyFinacialGoal;
use Modules\Goal\Events\UpdateFinacialGoal;

class GoalController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {

        if (\Auth::user()->can('goal manage')) {

            $golas = Goal::where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->get();


            return view('goal::goal.index', compact('golas'));
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
        if (\Auth::user()->can('goal create')) {
            $types = Goal::$goalType;

            return view('goal::goal.create', compact('types'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        if (\Auth::user()->can('goal create')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'name' => 'required',
                    'type' => 'required',
                    'from' => 'required',
                    'to' => 'required',
                    'amount' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $goal             = new Goal();
            $goal->name       = $request->name;
            $goal->type       = $request->type;
            $goal->from       = $request->from;
            $goal->to         = $request->to;
            $goal->amount     = $request->amount;
            $goal->is_display = isset($request->is_display) ? 1 : 0;
            $goal->workspace  = getActiveWorkSpace();
            $goal->created_by = creatorId();
            $goal->save();

            event(new CreateFinacialGoal($request, $goal));
            return redirect()->route('goal.index')->with('success', __('Goal successfully created.'));
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
        return redirect()->route('goal::goal.index');
        return view('goal::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {

        if (\Auth::user()->can('goal create')) {
            $goal = Goal::find($id);

            $types = Goal::$goalType;

            return view('goal::goal.edit', compact('types', 'goal'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
        return view('goal::goal.edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {

        if (\Auth::user()->can('goal edit')) {
            $goal = Goal::find($id);
            if ($goal->created_by == creatorId()) {
                $validator = \Validator::make(
                    $request->all(),
                    [
                        'name' => 'required',
                        'type' => 'required',
                        'from' => 'required',
                        'to' => 'required',
                        'amount' => 'required',
                    ]
                );
                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();

                    return redirect()->back()->with('error', $messages->first());
                }

                $goal->name       = $request->name;
                $goal->type       = $request->type;
                $goal->from       = $request->from;
                $goal->to         = $request->to;
                $goal->amount     = $request->amount;
                $goal->is_display = isset($request->is_display) ? 1 : 0;
                $goal->save();

                event(new UpdateFinacialGoal($request, $goal));
                return redirect()->route('goal.index')->with('success', __('Goal successfully updated.'));
            } else {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
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
        if (\Auth::user()->can('goal delete')) {
            $goal = Goal::find($id);
            event(new DestroyFinacialGoal($goal));
            if ($goal->created_by == creatorId() &&  $goal->workspace  == getActiveWorkSpace()) {
                $goal->delete();

                return redirect()->route('goal.index')->with('success', __('Goal successfully deleted.'));
            } else {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function fileImportExport()
    {
        if (Auth::user()->can('goal import')) {
            return view('goal::goal.import');
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function fileImport(Request $request)
    {
        if (Auth::user()->can('goal import')) {
            session_start();

            $error = '';

            $html = '';

            if ($request->file->getClientOriginalName() != '') {
                $file_array = explode(".", $request->file->getClientOriginalName());

                $extension = end($file_array);
                if ($extension == 'csv') {
                    $file_data = fopen($request->file->getRealPath(), 'r');

                    $file_header = fgetcsv($file_data);
                    $html .= '<table class="table table-bordered"><tr>';

                    for ($count = 0; $count < count($file_header); $count++) {
                        $html .= '
                                <th>
                                    <select name="set_column_data" class="form-control set_column_data" data-column_number="' . $count . '">
                                    <option value="">Set Count Data</option>
                                    <option value="name">Name</option>
                                    <option value="amount">Amount</option>
                                    <option value="from">From</option>
                                    <option value="to">To</option>
                                    </select>
                                </th>
                                ';
                    }

                    $html .= '
                                <th>
                                        <select name="set_column_data" class="form-control set_column_data type" data-column_number="' . $count + 1 . '">
                                            <option value="type">Type</option>
                                        </select>
                                </th>
                                ';

                    $html .= '</tr>';
                    $limit = 0;
                    while (($row = fgetcsv($file_data)) !== false) {
                        $limit++;

                        $html .= '<tr>';

                        for ($count = 0; $count < count($row); $count++) {
                            $html .= '<td>' . $row[$count] . '</td>';
                        }

                        $html .= '<td>
                                    <select name="type" class="form-control type-name-value">;';
                        $types = Goal::$goalType;
                        foreach ($types as $key => $type) {
                            $html .= ' <option value="' . $key . '">' . $type . '</option>';
                        }
                        $html .= '  </select>
                                </td>';

                        $html .= '</tr>';

                        $temp_data[] = $row;
                    }
                    $_SESSION['file_data'] = $temp_data;
                } else {
                    $error = 'Only <b>.csv</b> file allowed';
                }
            } else {

                $error = 'Please Select CSV File';
            }
            $output = array(
                'error' => $error,
                'output' => $html,
            );

            echo json_encode($output);
        } else {
            return redirect()->back()->with('error', 'permission Denied');
        }
    }

    public function fileImportModal()
    {
        if (Auth::user()->can('goal import')) {
            return view('goal::goal.import_modal');
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function goalImportdata(Request $request)
    {
        if (Auth::user()->can('goal import')) {
            session_start();
            $html = '<h3 class="text-danger text-center">Below data is not inserted</h3></br>';
            $flag = 0;
            $html .= '<table class="table table-bordered"><tr>';
            $file_data = $_SESSION['file_data'];

            unset($_SESSION['file_data']);

            $user = \Auth::user();
            foreach ($file_data as $key => $row) {
                $goal = Goal::where('created_by', creatorId())->where('workspace', getActiveWorkSpace())->Where('name', 'like', $row[$request->name])->get();

                if ($goal->isEmpty()) {
                    try {
                        $type = $request->type[$key];
                        Goal::create([
                            'name' => $row[$request->name],
                            'amount' => $row[$request->amount],
                            'type' => $type,
                            'from' => $row[$request->from],
                            'to' => $row[$request->to],
                            'created_by' => creatorId(),
                            'workspace' => getActiveWorkSpace(),
                        ]);
                    } catch (\Exception $e) {
                        $flag = 1;
                        $html .= '<tr>';

                        $html .= '<td>' . $row[$request->name] . '</td>';
                        $html .= '<td>' . $row[$request->amount] . '</td>';
                        $html .= '<td>' . $row[$request->from] . '</td>';
                        $html .= '<td>' . $row[$request->to] . '</td>';

                        $html .= '</tr>';
                    }
                } else {
                    $flag = 1;
                    $html .= '<tr>';

                    $html .= '<td>' . $row[$request->name] . '</td>';
                    $html .= '<td>' . $row[$request->amount] . '</td>';
                    $html .= '<td>' . $row[$request->from] . '</td>';
                    $html .= '<td>' . $row[$request->to] . '</td>';

                    $html .= '</tr>';
                }
            }

            $html .= '
                            </table>
                            <br />
                            ';
            if ($flag == 1) {

                return response()->json([
                    'html' => true,
                    'response' => $html,
                ]);
            } else {
                return response()->json([
                    'html' => false,
                    'response' => 'Data Imported Successfully',
                ]);
            }
        } else {
            return redirect()->back()->with('error', 'permission Denied');
        }
    }
}
