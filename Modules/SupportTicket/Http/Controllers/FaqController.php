<?php

namespace Modules\SupportTicket\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\SupportTicket\Entities\Faq;

class FaqController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if (Auth::user()->isAbleTo('faq manage')) {
            $faqs = Faq::where('created_by', creatorId())->where('workspace_id', getActiveWorkSpace())->get();

            return view('supportticket::faq.index', compact('faqs'));
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
        if (Auth::user()->isAbleTo('faq create')) {
            return view('supportticket::faq.create');
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
        if (Auth::user()->isAbleTo('faq create')) {
            $user = \Auth::user();
            $validation = [
                'title' => ['required', 'string', 'max:255'],
                'description' => ['required'],
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
                'workspace_id' => getActiveWorkSpace(),
                'created_by' => auth()->user()->id
            ];

            Faq::create($post);
            return redirect()->route('support-ticket-faq.index')->with('success',  __('Faq created successfully'));
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
        return redirect()->route('support-ticket-faq.index')->with('error', __('Permission denied.'));
        return view('supportticket::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $userObj = \Auth::user();
        if (Auth::user()->isAbleTo('faq edit')) {
            $faq = Faq::find($id);
            return view('supportticket::faq.edit', compact('faq'));
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
        if (Auth::user()->isAbleTo('faq edit')) {
            $userObj = \Auth::user();
            $post = [
                'title' => $request->title,
                'description' => $request->description
            ];
            $faq = Faq::find($id);
            $faq->update($post);
            return redirect()->route('support-ticket-faq.index')->with('success',  __('Faq updated successfully'));
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
        if (Auth::user()->isAbleTo('faq delete')) {
            $user = \Auth::user();

            $faq = Faq::find($id);
            $faq->delete();
            return redirect()->route('support-ticket-faq.index')->with('success', __('Faq deleted successfully'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function fileImportExport()
    {
        if (Auth::user()->isAbleTo('faq import')) {
            return view('supportticket::faq.import');
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function fileImport(Request $request)
    {
        if (Auth::user()->isAbleTo('faq import')) {
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
                    $html .= '</tr>';
                    $limit = 0;
                    while (($row = fgetcsv($file_data)) !== false) {
                        $limit++;

                        $html .= '<tr>';

                        for ($count = 0; $count < count($row); $count++) {
                            $html .= '<td>' . $row[$count] . '</td>';
                        }

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
        if (Auth::user()->isAbleTo('faq import')) {
            return view('supportticket::faq.import_modal');
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function faqImportdata(Request $request)
    {
        if (Auth::user()->isAbleTo('faq import')) {
            session_start();
            $html = '<h3 class="text-danger text-center">Below data is not inserted</h3></br>';
            $flag = 0;
            $html .= '<table class="table table-bordered"><tr>';
            $file_data = $_SESSION['file_data'];

            unset($_SESSION['file_data']);

            $user = \Auth::user();


            foreach ($file_data as $row) {
                $faq = Faq::where('created_by', creatorId())->where('workspace_id', getActiveWorkSpace())->Where('title', 'like', $row[$request->title])->get();

                if ($faq->isEmpty()) {

                    try {
                        Faq::create([
                            'title' => $row[$request->title],
                            'description' => $row[$request->description],
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
