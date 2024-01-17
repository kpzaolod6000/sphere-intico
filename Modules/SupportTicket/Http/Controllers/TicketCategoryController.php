<?php

namespace Modules\SupportTicket\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\SupportTicket\Entities\Ticket;
use Modules\SupportTicket\Entities\TicketCategory;

class TicketCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if (Auth::user()->isAbleTo('ticketcategory manage')) {
            $categories = TicketCategory::where('created_by', creatorId())->where('workspace_id', getActiveWorkSpace())->get();
            return view('supportticket::category.index', compact('categories'));
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
        if (Auth::user()->isAbleTo('ticketcategory create')) {
            return view('supportticket::category.create');
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
        if (Auth::user()->isAbleTo('ticketcategory create')) {
            $user = \Auth::user();

            $validation = [
                'name' => [
                    'required',
                    'string',
                    'max:255',
                ],
                'color' => [
                    'required',
                    'string',
                    'max:255',
                ],
            ];
            $request->validate($validation);

            $post = [
                'name' => $request->name,
                'color' => $request->color,
                'workspace_id' => getActiveWorkSpace(),
                'created_by' => creatorId(),
            ];

            TicketCategory::create($post);

            return redirect()->route('ticket-category.index')->with('success', __('Category created successfully'));
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
        return redirect()->route('ticket-category.index')->with('error', __('Permission denied.'));

        return view('supportticket::category.show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        if (Auth::user()->isAbleTo('ticketcategory edit')) {
            $userObj = \Auth::user();
            $category = TicketCategory::find($id);
            return view('supportticket::category.edit', compact('category'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
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
        if (Auth::user()->isAbleTo('ticketcategory edit')) {
            $category        = TicketCategory::find($id);
            $category->name  = $request->name;
            $category->color = $request->color;

            $category->save();

            return redirect()->route('ticket-category.index')->with('success', __('Category updated successfully'));
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
        if (Auth::user()->isAbleTo('ticketcategory delete')) {
            $tickets = Ticket::where('category', $id)->get();
            if (count($tickets) == 0) {
                $category = TicketCategory::find($id);
                $category->delete();
                return redirect()->route('ticket-category.index')->with('success', __('Category deleted successfully'));
            } else {
                return redirect()->route('ticket-category.index')->with('error', __('This Category is Used on Ticket.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
