<?php

namespace Modules\SupportTicket\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\SupportTicket\Entities\KnowledgeBase;
use Modules\SupportTicket\Entities\KnowledgeBaseCategory;

class KnowledgebaseCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if (Auth::user()->isAbleTo('knowledgebasecategory manage')) {
            // $knowledges_category = KnowledgeBaseCategory::where('workspace_id', getActiveWorkSpace())->get();
            $knowledges_category = KnowledgeBaseCategory::where('created_by', creatorId())->where('workspace_id', getActiveWorkSpace())->get();
            return view('supportticket::knowledgecategory.index', compact('knowledges_category'));
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
        if (Auth::user()->isAbleTo('knowledgebasecategory create')) {
            return view('supportticket::knowledgecategory.create');
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

        if (\Auth::user()->isAbleTo('knowledgebasecategory create')) {
            $user = \Auth::user();
            $validation = [
                'title' => ['required', 'string', 'max:255'],
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
                'workspace_id' => getActiveWorkSpace(),
                'created_by' => creatorId(),
            ];
            KnowledgeBaseCategory::create($post);
            return redirect()->route('knowledge-category.index')->with('success', __('KnowledgeBase Category created successfully'));
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
        return redirect()->route('knowledge-category.index')->with('error', __('Permission denied.'));

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
        if (Auth::user()->isAbleTo('knowledgebasecategory edit')) {
            $knowledge_category = KnowledgeBaseCategory::find($id);
            return view('supportticket::knowledgecategory.edit', compact('knowledge_category'));
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
        $userObj = \Auth::user();
        if (Auth::user()->isAbleTo('knowledgebasecategory edit')) {
            $knowledge_category = KnowledgeBaseCategory::find($id);
            $knowledge_category->update($request->all());
            return redirect()->route('knowledge-category.index')->with('success', __('KnowledgeBase Category updated successfully'));
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
        $user = \Auth::user();
        if (Auth::user()->isAbleTo('knowledgebasecategory delete')) {
            $knowledge = KnowledgeBase::where('category', $id)->count();
            if ($knowledge == 0) {
                $knowledge_category = Knowledgebasecategory::find($id);
                $knowledge_category->delete();
                return redirect()->route('knowledge-category.index')->with('success', __('KnowledgeBase Category deleted successfully'));
            } else {
                return redirect()->back()->with('error', __('This KnowledgeBase Category is Used on Knowledge Base.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
