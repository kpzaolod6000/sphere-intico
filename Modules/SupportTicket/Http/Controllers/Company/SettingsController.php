<?php
// This file use for handle company setting page

namespace Modules\SupportTicket\Http\Controllers\Company;
use Illuminate\Http\Request;
use Modules\SupportTicket\Entities\TicketField;
use Illuminate\Routing\Controller;

class SettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($settings)
    {
        $fields = TicketField::where('workspace_id',getActiveWorkSpace())->where('created_by',\Auth::user()->id)->orderBy('order')->get();
        if($fields->count() < 1)
        {
            TicketField::defultadd();
        }

        return view('supportticket::company.settings.index',compact('settings','fields'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }
}
