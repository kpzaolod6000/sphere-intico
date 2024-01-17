<?php

namespace Modules\SupportTicket\Http\Controllers;

use App\Models\EmailTemplate;
use App\Models\WorkSpace;
use App\Models\User;
use App\Models\Setting;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Crypt;
use Modules\CustomField\Entities\CustomField;
use Modules\SupportTicket\Entities\Conversion;
use Modules\SupportTicket\Entities\Faq;
use Modules\SupportTicket\Entities\KnowledgeBase;
use Modules\SupportTicket\Entities\Ticket;
use Modules\SupportTicket\Entities\TicketField;
use Modules\SupportTicket\Entities\TicketCategory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Modules\SupportTicket\Emails\SendTicket;
use Modules\SupportTicket\Emails\SendTicketReply;
use Modules\SupportTicket\Events\CreatePublicTicket;
use Modules\SupportTicket\Events\ReplyPublicTicket;
use PDO;
use Rawilk\Settings\Support\Context;

class PublicTicketController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index($slug, $ticket_id)
    {
        $ticket_id = Crypt::decrypt($ticket_id);
        $ticket    = Ticket::where('ticket_id', '=', $ticket_id)->first();
        $workspace = WorkSpace::where('slug', $slug)->first();
        if ($ticket) {
            $faq = Setting::where('key', 'faq_is_on')->where('workspace', $workspace->id)->value('value');
            $knowledge = Setting::where('key', 'knowledge_base_is_on')->where('workspace', $workspace->id)->value('value');

            return view('supportticket::show', compact('ticket', 'workspace','faq','knowledge'));
        } else {
            return redirect()->back()->with('error', __('Some thing is wrong'));
        }
    }

    /**
                                                                                                                                                                                                                                                                                                             * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create($slug)
    {
        try {
            $slug = $slug;
        } catch (\Throwable $th) {
            return redirect('login');
        }
        $workspace = WorkSpace::where('slug', $slug)->first();
        $categories   = TicketCategory::where('created_by', $workspace->created_by)->where('workspace_id', $workspace->id)->get();
        $fields       = TicketField::where('workspace_id', $workspace->id)->orderBy('order')->get();
        // $userContext = new Context(['user_id' => $workspace->created_by, 'workspace_id' => $workspace->id]);
        // $faq = settings()->context($userContext)->get('faq_is_on');
        // $knowledge = settings()->context($userContext)->get('knowledge_base_is_on');

        $faq = Setting::where('key', 'faq_is_on')->where('workspace', $workspace->id)->value('value');
        $knowledge = Setting::where('key', 'knowledge_base_is_on')->where('workspace', $workspace->id)->value('value');

        return view('supportticket::index', compact('categories', 'fields', 'workspace','knowledge','faq'));
    }


    public function store(Request $request)
    {
        $workSpace = WorkSpace::where('slug', $request->slug)->first();
        $user = User::where('id', $workSpace->created_by)->first();

        $validation = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'category' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'status' => 'required|string|max:100',
            'description' => 'required',
        ];

        $validator = \Validator::make(
            $request->all(),
            $validation
        );
        $post              = $request->all();
        $post['ticket_id'] = time();
        $post['created_by'] = $user->id;
        $post['workspace_id'] = $workSpace->id;
        $post['account_type'] = 'custom';


        $data              = [];
        if ($request->hasfile('attachments')) {
            foreach ($request->file('attachments') as $file) {

                $name = $file->getClientOriginalName();
                $data[] = [
                    'name' => $name,
                    'path' => 'uploads/tickets/' . $post['ticket_id'] . '/' . $name
                ];
                multi_upload_file($file, 'attachments', $name, 'tickets/' . $post['ticket_id']);
            }
        }
        $post['attachments'] = json_encode($data);
        $ticket              = Ticket::create($post);
        TicketField::saveData($ticket, $request->fields);
        $ticket_url = route('ticket.view', [$ticket->workspace->slug, \Illuminate\Support\Facades\Crypt::encrypt($ticket->ticket_id)]);
        event(new CreatePublicTicket($request,$ticket));

       // Send Email to User

            try {
                $setconfing =  SetConfigEmail();
                if ($setconfing ==  true) {
                    try {
                        if(Auth::check())
                        {
                            $user        = User::where('id', Auth::user()->id)->where('workspace_id', '=',  getActiveWorkSpace())->first();
                        }
                        else
                        {
                            $user        = User::where('id', $ticket->created_by)->where('workspace_id',$ticket->workspace_id)->first();

                        }
                        Mail::to($user->email)->send(new SendTicket($ticket));

                    } catch (\Exception $e) {

                        $smtp_error['status'] = false;
                        $smtp_error['msg'] = $e->getMessage();
                    }
                } else {
                    $smtp_error['status'] = false;
                    $smtp_error['msg'] = __('Something went wrong please try again ');
                }
            } catch (\Exception $e) {
                $smtp_error['status'] = false;
                $smtp_error['msg'] = $e->getMessage();
            }
        return redirect()->back()->with('create_ticket', __('Ticket created successfully') . ' <a href="' . route('ticket.view', [$ticket->workspace->slug, \Illuminate\Support\Facades\Crypt::encrypt($ticket->ticket_id)]) . '"><b>' . __('Your unique ticket link is this.') . '</b></a> ' . ((isset($error_msg)) ? '<br> <span class="text-danger">' . $error_msg . '</span>' : ''));
    }



    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($slug, $id)
    {
        return view('supportticket::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($slug, $id)
    {
        return view('supportticket::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }

    public function reply($slug, $ticket_id, Request $request)
    {

        $ticket = Ticket::where('ticket_id', '=', $ticket_id)->first();
        if ($ticket) {
            $validation = [
                'reply_description' => 'required'
            ];
            $validator = \Validator::make(
                $request->all(),
                $validation
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->withInput()->with('error', $messages->first());
            }

            $post                = [];
            $post['sender']      = 'user';
            $post['ticket_id']   = $ticket->id;
            $post['description'] = $request->reply_description;
            $data                = [];
            if ($request->hasfile('reply_attachments')) {
                foreach ($request->file('reply_attachments') as $file) {

                    $name = $file->getClientOriginalName();
                    $data[] = [
                        'name' => $name,
                        'path' => 'uploads/tickets/' . $ticket->ticket_id . '/' . $name
                    ];
                    multi_upload_file($file, 'attachments', $name, 'tickets/' . $ticket->ticket_id);
                }
            }
            $post['attachments'] = json_encode($data);
            $conversion          = Conversion::create($post);

            // Send Email to User
            try {
                $setconfing =  SetConfigEmail();
                if ($setconfing ==  true) {
                    try {
                        if(Auth::check())
                        {
                            $user        = User::where('id', Auth::user()->id)->where('workspace_id', '=',  getActiveWorkSpace())->first();
                        }
                        else
                        {
                            $user        = User::where('id', $ticket->created_by)->where('workspace_id',$ticket->workspace_id)->first();
                        }

                        Mail::to($user->email)->send(new SendTicketReply($ticket, $conversion));
                        // dd($user->email);

                    } catch (\Exception $e) {
                        $smtp_error['status'] = false;
                        $smtp_error['msg'] = $e->getMessage();
                    }
                } else {
                    $smtp_error['status'] = false;
                    $smtp_error['msg'] = __('Something went wrong please try again ');
                }
            } catch (\Exception $e) {
                $smtp_error['status'] = false;
                $smtp_error['msg'] = $e->getMessage();
            }

            return redirect()->back()->with('success', __('Reply added successfully') . ((isset($error_msg)) ? '<br> <span class="text-danger">' . $error_msg . '</span>' : ''));
        } else {
            return redirect()->back()->with('error', __('Something is wrong'));
        }
    }
    public function search($slug)
    {

        $workspace = WorkSpace::where('slug', $slug)->first();
        return view('supportticket::search', compact('workspace'));
    }
    public function ticketSearch($slug, Request $request)
    {
        $validation = [
            'ticket_id' => ['required'],
            'email' => ['required'],
        ];

        $validator = \Validator::make(
            $request->all(),
            $validation
        );
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return redirect()->back()->withInput()->with('error', $messages->first());
        }
        $ticket = Ticket::where('ticket_id', '=', $request->ticket_id)->where('email', '=', $request->email)->first();

        if ($ticket) {
            return redirect()->route('ticket.view', [$slug, Crypt::encrypt($ticket->ticket_id)]);
        } else {
            return redirect()->back()->with('info', __('Invalid Ticket Number'));
        }

        return view('search');
    }
    public function faq($slug)
    {

        $workspace = WorkSpace::where('slug', $slug)->first();
        $faqs = Faq::where('workspace_id', $workspace->id)->get();
        $faq = Setting::where('key', 'faq_is_on')->where('workspace', $workspace->id)->value('value');
        $knowledge = Setting::where('key', 'knowledge_base_is_on')->where('workspace', $workspace->id)->value('value');
        return view('supportticket::faq', compact('workspace', 'faqs', 'knowledge', 'faq'));
    }
    public function knowledge($slug)
    {
        $workspace = WorkSpace::where('slug', $slug)->first();

        $faq = Setting::where('key', 'faq_is_on')->where('workspace', $workspace->id)->value('value');
        $knowledge = Setting::where('key', 'knowledge_base_is_on')->where('workspace', $workspace->id)->value('value');
        $knowledges = KnowledgeBase::select('category')->where('workspace_id', $workspace->id)->groupBy('category')->get();
        $knowledges_detail = KnowledgeBase::where('workspace_id', $workspace->id)->get();

        return view('supportticket::knowledge', compact('workspace', 'knowledges', 'knowledges_detail', 'knowledge', 'faq'));
    }
    public function knowledgeDescription($slug, Request $request)
    {
        $descriptions = KnowledgeBase::find($request->id);
        $workspace = WorkSpace::where('slug', $slug)->first();
        $userContext = new Context(['user_id' => $workspace->created_by, 'workspace_id' => $workspace->id]);
        $faq = settings()->context($userContext)->get('faq_is_on');
        $knowledge = settings()->context($userContext)->get('knowledge_base_is_on');
        return view('supportticket::knowledgedesc', compact('descriptions', 'workspace', 'faq', 'knowledge'));
    }
}
