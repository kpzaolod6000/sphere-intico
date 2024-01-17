<?php

namespace Modules\SupportTicket\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\SupportTicket\Entities\KnowledgeBase;
use Modules\SupportTicket\Entities\KnowledgeBaseCategory;

class KnowledgeController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if (Auth::user()->isAbleTo('knowledgebase manage')) {
            $knowledges = KnowledgeBase::where('created_by', creatorId())->where('workspace_id', getActiveWorkSpace())->get();

            return view('supportticket::knowledge.index', compact('knowledges'));
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
        if (Auth::user()->isAbleTo('knowledgebase create')) {
            $category = KnowledgeBaseCategory::where('workspace_id', getActiveWorkSpace())->get();
            return view('supportticket::knowledge.create', compact('category'));
        } else {
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
        if (Auth::user()->isAbleTo('knowledgebase create')) {
            $user = \Auth::user();
            $validation = [
                'title' => ['required', 'string', 'max:255'],
                'description' => ['required'],
                'category' => ['required', 'string', 'max:255'],
            ];
            $validator = \Validator::make(
                $request->all(),
                $validation
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->withInput()->with('error', $messages->first());
            }

            $post = [
                'title' => $request->title,
                'description' => $request->description,
                'category' => $request->category,
                'workspace_id' => getActiveWorkSpace(),
                'created_by' => creatorId(),
            ];

            KnowledgeBase::create($post);
            return redirect()->route('support-ticket-knowledge.index')->with('success',  __('Knowledge created successfully'));
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
        return redirect()->route('support-ticket-knowledge.index')->with('error', __('Permission denied.'));
        return view('supportticket::knowledge.show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        if (Auth::user()->isAbleTo('knowledgebase edit')) {
            $userObj = \Auth::user();
            $knowledge = KnowledgeBase::find($id);
            $category = KnowledgeBaseCategory::get();
            return view('supportticket::knowledge.edit', compact('knowledge', 'category'));
        } else {
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
        if (Auth::user()->isAbleTo('knowledgebase edit')) {
            $userObj = \Auth::user();
            $knowledge = KnowledgeBase::find($id);
            $knowledge->update($request->all());
            return redirect()->route('support-ticket-knowledge.index')->with('success', __('Knowledge updated successfully'));
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        if (Auth::user()->isAbleTo('knowledgebase delete')) {
            $knowledge = KnowledgeBase::find($id);
            $knowledge->delete();
            return redirect()->route('support-ticket-knowledge.index')->with('success', __('KnowledgeBase deleted successfully'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function fileImportExport()
    {
        if (Auth::user()->isAbleTo('knowledgebase import')) {
            return view('supportticket::knowledge.import');
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function fileImport(Request $request)
    {
        if (Auth::user()->isAbleTo('knowledgebase import')) {
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
                                    <option value="title">Title</option>
                                    <option value="description">Description</option>
                                    </select>
                                </th>
                                ';
                    }

                    $html .= '
                                <th>
                                        <select name="set_column_data" class="form-control set_column_data category-name" data-column_number="' . $count + 1 . '">
                                            <option value="category">Category</option>
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
                                    <select name="category" class="form-control category-name-value">;';
                        $knowlagebases = KnowledgeBaseCategory::where('workspace_id', getActiveWorkSpace())->pluck('title', 'id');
                        foreach ($knowlagebases as $key => $knowlagebase) {
                            $html .= ' <option value="' . $key . '">' . $knowlagebase . '</option>';
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
        if (Auth::user()->isAbleTo('knowledgebase import')) {
            return view('supportticket::knowledge.import_modal');
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function knowledgeImportdata(Request $request)
    {
        if (Auth::user()->isAbleTo('knowledgebase import')) {
            session_start();
            $html = '<h3 class="text-danger text-center">Below data is not inserted</h3></br>';
            $flag = 0;
            $html .= '<table class="table table-bordered"><tr>';
            $file_data = $_SESSION['file_data'];

            unset($_SESSION['file_data']);

            foreach ($file_data as $key => $row) {
                $knowlage = KnowledgeBase::where('workspace_id', getActiveWorkSpace())->Where('title', 'like', $row[$request->title])->get();

                if ($knowlage->isEmpty()) {
                    try {

                        $category = KnowledgeBaseCategory::find($request->category[$key]);
                        KnowledgeBase::create([
                            'title' => $row[$request->title],
                            'description' => $row[$request->description],
                            'category' => $category->id,
                            'created_by' => creatorId(),
                            'workspace_id' => getActiveWorkSpace(),
                        ]);
                    } catch (\Exception $e) {
                        $flag = 1;
                        $html .= '<tr>';

                        $html .= '<td>' . $row[$request->title] . '</td>';
                        $html .= '<td>' . $row[$request->description] . '</td>';

                        $html .= '</tr>';
                    }
                } else {
                    $flag = 1;
                    $html .= '<tr>';

                    $html .= '<td>' . $row[$request->title] . '</td>';
                    $html .= '<td>' . $row[$request->description] . '</td>';

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
