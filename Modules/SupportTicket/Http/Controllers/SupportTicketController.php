<?php

namespace Modules\SupportTicket\Http\Controllers;

use App\Models\EmailTemplate;
use App\Models\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Modules\SupportTicket\Entities\Conversion;
use Modules\SupportTicket\Entities\Ticket;
use Modules\SupportTicket\Entities\TicketCategory;
use Modules\SupportTicket\Entities\TicketField;
use Modules\SupportTicket\Events\CreateTicket;
use Modules\SupportTicket\Events\DestroyTicket;
use Modules\SupportTicket\Events\UpdateTicket;
use App\Models\Setting;


class SupportTicketController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index($status = '')
    {
        if (Auth::user()->isAbleTo('ticket manage')) {
            $tickets = Ticket::with('workspace')->select(
                [
                    'tickets.*',
                    'ticket_categories.name as category_name',
                    'ticket_categories.color',
                ]
            )->join('ticket_categories', 'ticket_categories.id', '=', 'tickets.category');
            if ($status == 'in-progress') {
                $tickets->where('status', '=', 'In Progress');
            } elseif ($status == 'on-hold') {
                $tickets->where('status', '=', 'On Hold');
            } elseif ($status == 'closed') {
                $tickets->where('status', '=', 'Closed');
            }
            $tickets = $tickets->where('tickets.workspace_id', getActiveWorkSpace())->orderBy('id', 'desc')->get();

            return view('supportticket::ticket.index', compact('tickets', 'status'));
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
        if (Auth::user()->isAbleTo('ticket create')) {
            $fields = TicketField::where('created_by', creatorId())->where('custom_id', '>', '6')->get();
            $categories = TicketCategory::where('created_by', creatorId())->where('workspace_id', getActiveWorkSpace())->get();
            $staff = User::where('created_by', creatorId())->where('workspace_id', getActiveWorkSpace())->where('type', 'staff')->get()->pluck('name', 'id');
            $client = User::where('created_by', creatorId())->where('workspace_id', getActiveWorkSpace())->where('type', 'client')->get()->pluck('name', 'id');
            $vendor = User::where('created_by', creatorId())->where('workspace_id', getActiveWorkSpace())->where('type', 'vendor')->get()->pluck('name', 'id');
            return view('supportticket::ticket.create', compact('categories', 'fields', 'staff', 'client', 'vendor'));
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

        $user = \Auth::user();
        if (Auth::user()->isAbleTo('ticket create')) {
            if ($request->account_type == 'custom') {
                $validation = [
                    'name' => 'required|string|max:255',
                    'email' => 'required|string|email|max:255',
                    'category' => 'required|string|max:255',
                    'subject' => 'required|string|max:255',
                    'status' => 'required|string|max:100',
                ];

                $validator = \Validator::make(
                    $request->all(),
                    $validation
                );
                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();
                    return redirect()->back()->withInput()->with('error', $messages->first());
                }
            }
            $post = $request->all();
            $post['ticket_id'] = time();
            $post['created_by'] = $user->id;
            $post['workspace_id'] = getActiveWorkSpace();
            if ($post['account_type'] == 'staff') {
                $post['user_id'] = $post['staff_name'];
                $post['name'] = User::find($post['staff_name'])->name;
            } elseif ($post['account_type'] == 'client') {
                $post['user_id'] = $post['client_name'];
                $post['name'] = User::find($post['client_name'])->name;
            } elseif ($post['account_type'] == 'vendor') {
                $post['user_id'] = $post['vendor_name'];
                $post['name'] = User::find($post['vendor_name'])->name;
            } elseif ($post['account_type'] == 'custom') {
                $post['name'] = $request->input('name');
            }
            $data = [];
            if ($request->hasfile('attachments')) {
                foreach ($request->file('attachments') as $file) {

                    $name = $file->getClientOriginalName();
                    $data[] = [
                        'name' => $name,
                        'path' => 'uploads/tickets/' . $post['ticket_id'] . '/' . $name,
                    ];
                    multi_upload_file($file, 'attachments', $name, 'tickets/' . $post['ticket_id']);
                }
            }
            $post['attachments'] = json_encode($data);
            $ticket = Ticket::create($post);
            TicketField::saveData($ticket, $request->fields);

            // first parameter request second parameter ticket
            event(new CreateTicket($request, $ticket));

            if (!empty(company_setting('New Ticket')) && company_setting('New Ticket') == true) {
                $user = User::where('id', $ticket->created_by)->where('workspace_id', '=', getActiveWorkSpace())->first();

                $uArr = [
                    'ticket_name' => $request->name,
                    'email' => $request->email,
                    'ticket_id' => $ticket->ticket_id,
                    'ticket_url' => route('dashboard.support-tickets', \Illuminate\Support\Facades\Crypt::encrypt($ticket->ticket_id)),
                ];

                try {
                    $resp = EmailTemplate::sendEmailTemplate('New Ticket', [$request->email], $uArr);
                } catch (\Exception $e) {
                    $resp['error'] = $e->getMessage();
                }

                // Send Email to
                if (isset($resp['error'])) {
                    session('smtp_error', '<span class="text-danger ml-2">' . $resp['error'] . '</span>');
                }
            }
            Session::put('ticket_id', ' <a class="text text-primary" target="_blank" href="' . route('ticket.view', [$ticket->workspace->slug, \Illuminate\Support\Facades\Crypt::encrypt($ticket->ticket_id)]) . '"><b>' . __('Your unique ticket link is this.') . '</b></a>');
            return redirect()->route('support-tickets.index')->with('success', __('Ticket created successfully.'));
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
        return redirect()->route('support-tickets.index');

        return view('supportticket::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit(Ticket $ticket, $id)
    {
        $user = \Auth::user();
        if (Auth::user()->isAbleTo('ticket show')) {
            $ticket = Ticket::find($id);
            if ($ticket) {
                $fields = TicketField::where('created_by', creatorId())->where('workspace_id', '=', getActiveWorkSpace())->where('custom_id', '>', '6')->get();
                $ticket->fields = TicketField::getData($ticket);
                $categories = TicketCategory::where('created_by', creatorId())->where('workspace_id', getActiveWorkSpace())->get();
                $staff = User::where('created_by', creatorId())->where('workspace_id', getActiveWorkSpace())->where('type', 'staff')->get()->pluck('name', 'id');
                $client = User::where('created_by', creatorId())->where('workspace_id', getActiveWorkSpace())->where('type', 'client')->get()->pluck('name', 'id');
                $vendor = User::where('created_by', creatorId())->where('workspace_id', getActiveWorkSpace())->where('type', 'vendor')->get()->pluck('name', 'id');
                return view('supportticket::ticket.edit', compact('ticket', 'categories', 'fields', 'staff', 'client', 'vendor'));
            } else {
                return view('403');
            }
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
        if (Auth::user()->isAbleTo('ticket edit')) {
            $ticket = Ticket::find($id);

            if ($request->account_type == 'custom') {
                $validation = [
                    'name' => 'required|string|max:255',
                    'email' => 'required|string|email|max:255',
                    'category' => 'required|string|max:255',
                    'subject' => 'required|string|max:255',
                    'status' => 'required|string|max:100',
                ];
                $validator = \Validator::make(
                    $request->all(),
                    $validation
                );
                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();
                    return redirect()->back()->withInput()->with('error', $messages->first());
                }
            }


            if ($request->hasfile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $name = $file->getClientOriginalName();
                    $data[] = [
                        'name' => $name,
                        'path' => 'uploads/tickets/' . $ticket->ticket_id . '/' . $name,
                    ];
                    multi_upload_file($file, 'attachments', $name, 'tickets/' . $ticket->ticket_id);
                }
                if ($request->hasfile('attachments')) {
                    $json_decode = json_decode($ticket->attachments);
                    $attachments = json_encode(array_merge($json_decode, $data));
                } else {
                    $attachments = json_encode($data);
                }
                $ticket->attachments = isset($attachments) ? $attachments : null;
            }



            TicketField::saveData($ticket, $request->fields);

            event(new UpdateTicket($request, $ticket));

            if ($request->account_type == 'custom') {
                $ticket->name = !empty($request->name) ? $request->name : '';
            } elseif ($request->account_type == 'staff') {
                $ticket->user_id = $request->staff_name;
                $ticket->name = User::find($request->staff_name)->name;
            } elseif ($request->account_type == 'client') {
                $ticket->user_id = $request->client_name;
                $ticket->name = User::find($request->client_name)->name;
            } elseif ($request->account_type == 'vendor') {
                $ticket->user_id = $request->vendor_name;
                $ticket->name = User::find($request->vendor_name)->name;
            }

            $ticket->email = !empty($request->email) ? $request->email : '';
            $ticket->category = !empty($request->category) ? $request->category : '';
            $ticket->subject = !empty($request->subject) ? $request->subject : '';
            $ticket->status = !empty($request->status) ? $request->status : '';
            $ticket->description = !empty($request->description) ? $request->description : '';
            $ticket->save();

            return redirect()->back()->with('success', __('Ticket Update  successfully'));
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
        if (Auth::user()->isAbleTo('ticket delete')) {
            $ticket = Ticket::find($id);
            $conversions = Conversion::where('ticket_id', $ticket->id)->get();
            if (count($conversions) > 0) {
                $conversions = Conversion::where('ticket_id', $ticket->id)->delete();
            }
            delete_folder('tickets/' . $ticket->ticket_id);

            event(new DestroyTicket($ticket));

            $ticket->delete();
            return redirect()->back()->with('success', __('Ticket Deleted successfully'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function attachmentDestroy($ticket_id, $id)
    {
        $user = \Auth::user();
        $ticket = Ticket::find($ticket_id);
        $attachments = json_decode($ticket->attachments);
        if (isset($attachments[$id])) {
            delete_file($attachments[$id]->path);
            unset($attachments[$id]);

            $ticket->attachments = json_encode(array_values($attachments));
            $ticket->save();

            return redirect()->back()->with('success', __('Attachment deleted successfully'));
        } else {
            return redirect()->back()->with('error', __('Attachment is missing'));
        }
    }
    public function storeNote($ticketID, Request $request)
    {

        $user = \Auth::user();

        $validation = [
            'note' => ['required'],
        ];
        $validator = \Validator::make(
            $request->all(),
            $validation
        );
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return redirect()->back()->withInput()->with('error', $messages->first());
        }

        $ticket = Ticket::find($ticketID);
        if ($ticket) {
            $ticket->note = $request->note;
            $ticket->save();

            return redirect()->back()->with('success', __('Ticket note saved successfully'));
        } else {
            return view('403');
        }
    }


    public function storeCustomFields(Request $request)
    {

        $order = 0;

        // $getActiveWorkSpace = getActiveWorkSpace();
        // $creatorId = creatorId();

        if($request->has('faq_is_on')){
            $post = $request->all();
            unset($post['_token']);
            unset($post['knowledge_base_is_on']);
            unset($post['fields']);
            unset($post['_method']);
            foreach ($post as $key => $value) {
                // Define the data to be updated or inserted
                $data = [
                    'key' => $key,
                    'workspace' => getActiveWorkSpace(),
                    'created_by' => creatorId(),
                ];

                // Check if the record exists, and update or insert accordingly
                Setting::updateOrInsert($data, ['value' => $value]);
            }
        }
        else
        {
            // Define the data to be updated or inserted
           $data = [
                'key' => 'faq_is_on',
                'workspace' => getActiveWorkSpace(),
                'created_by' => creatorId(),
            ];

            // Check if the record exists, and update or insert accordingly
            Setting::updateOrInsert($data, ['value' => 'off']);

        }

        if($request->has('knowledge_base_is_on')){
            $post = $request->all();
            unset($post['_token']);
            unset($post['faq_is_on']);
            unset($post['fields']);
            unset($post['_method']);

            foreach ($post as $key => $value) {
                // Define the data to be updated or inserted
                $data = [
                    'key' => $key,
                    'workspace' => getActiveWorkSpace(),
                    'created_by' => creatorId(),
                ];

                // Check if the record exists, and update or insert accordingly
                Setting::updateOrInsert($data, ['value' => $value]);
            }
        }
        else
        {
            // Define the data to be updated or inserted
           $data = [
                'key' => 'knowledge_base_is_on',
                'workspace' => getActiveWorkSpace(),
                'created_by' => creatorId(),
            ];

            // Check if the record exists, and update or insert accordingly
            Setting::updateOrInsert($data, ['value' => 'off']);

        }



        foreach ($request->fields as $key => $field) {
            $f = TicketField::where('workspace_id', getActiveWorkSpace())->where('id', $field['id'])->first();
            $only = TicketField::find($field['id']);

            if ($f) {
                $f->name = $field['name'];
                $f->placeholder = $field['placeholder'];
                $f->width = $field['width'];
                $f->order = $order;
                $f->workspace_id = getActiveWorkSpace();
                $f->save();
                $order++;
            } elseif ($only) {
                $new = $only->replicate();
                $new->name = $field['name'];
                $new->placeholder = $field['placeholder'];
                $new->width = $field['width'];
                $new->order = $order;
                $new->workspace_id = getActiveWorkSpace();
                $new->save();
                $order++;
            }
        }

        $rules = [
            'fields' => 'required|present|array',
        ];
        $attributes = [];

        if ($request->fields) {
            foreach ($request->fields as $key => $val) {
                $rules['fields.' . $key . '.name'] = 'required|max:255';
                $attributes['fields.' . $key . '.name'] = __('Field Name');
            }
        }

        $validator = \Validator::make($request->all(), $rules, [], $attributes);
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }

        $field_ids = TicketField::where('created_by', creatorId())->orderBy('order')->pluck('id')->toArray();

        $order = 0;
        $custom_id = 1;
        $id = 1;
        foreach ($request->fields as $key => $field) {

            $fieldObj = new TicketField();



            if (isset($field['id']) && !empty($field['id'])) {
                $fieldObj = TicketField::find($field['id']);

                if (($key = array_search($fieldObj->id, $field_ids)) !== false) {
                    unset($field_ids[$key]);
                }
            }

            $fieldObj->name = $field['name'];
            $fieldObj->placeholder = $field['placeholder'];
            if (isset($field['type']) && !empty($field['type'])) {
                if (isset($fieldObj->id) && $fieldObj->id > 6) {
                    $fieldObj->type = $field['type'];
                } elseif (!isset($fieldObj->id)) {
                    $fieldObj->type = $field['type'];
                }
            }
            $fieldObj->width = (isset($field['width'])) ? $field['width'] : '12';


            if (isset($field['status'])) {
                if (isset($fieldObj->id) && $fieldObj->id > 7) {
                    $fieldObj->status = $field['status'];
                } elseif (!isset($fieldObj->id)) {
                    $fieldObj->status = $field['status'];
                }
            }


            $fieldObj->created_by = Auth::id();
            $fieldObj->order      = $order++;
            $fieldObj->workspace_id = getActiveWorkSpace();
            $fieldObj->save();
            if ($fieldObj->custom_id == 0) {
                $fieldObj->custom_id      = $fieldObj->id;
                $fieldObj->save();
            }


        }


        if (!empty($field_ids) && count($field_ids) > 0) {
            TicketField::whereIn('id', $field_ids)->where('status', 1)->delete();
        }

        // Settings Cache forget
        AdminSettingCacheForget();
        comapnySettingCacheForget();

        return redirect()->back()->with('success', __('Fields Saves Successfully.!'));
    }

    public function grid($status = '')
    {
        if (Auth::user()->isAbleTo('ticket manage')) {
            $tickets = Ticket::with('workspace')->select(
                [
                    'tickets.*',
                    'ticket_categories.name as category_name',
                    'ticket_categories.color',
                ]
            )->join('ticket_categories', 'ticket_categories.id', '=', 'tickets.category');
            if ($status == 'in-progress') {
                $tickets->where('status', '=', 'In Progress');
            } elseif ($status == 'on-hold') {
                $tickets->where('status', '=', 'On Hold');
            } elseif ($status == 'closed') {
                $tickets->where('status', '=', 'Closed');
            }
            $tickets = $tickets->where('tickets.workspace_id', getActiveWorkSpace())->orderBy('id', 'desc')->get();

            return view('supportticket::ticket.grid', compact('tickets', 'status'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function  getUser(Request $request)
    {
        $user = User::find($request->user_id);
        if ($user) {
            $userData = [
                'name' => $user->name,
                'email' => $user->email,
            ];
            return response()->json($userData);
        } else {
            return response()->json(['error' => 'User not found']);
        }
    }
}
