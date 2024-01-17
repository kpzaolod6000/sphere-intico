<?php

namespace Modules\SupportTicket\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Mail;
use Modules\SupportTicket\Entities\Conversion;
use Modules\SupportTicket\Entities\Ticket;
use App\Models\EmailTemplate;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Modules\SupportTicket\Entities\TicketCategory;
use Modules\SupportTicket\Events\ReplyTicket;

class ConversionController extends Controller
{

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request, $ticket_id)
    {

        $user = Auth::user();

        $ticket = Ticket::find($ticket_id);

        if ($ticket) {
            $validation = ['reply_description' => ['required']];
            $validator = \Validator::make(
                $request->all(),
                $validation
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->withInput()->with('error', $messages->first());
            }
            $post = [];
            $post['sender'] = ($user) ? $user->id : 'user';
            $post['ticket_id'] = $ticket->id;
            $post['description'] = $request->reply_description;
            $data = [];
            if ($request->hasfile('reply_attachments')) {
                foreach ($request->file('reply_attachments') as $file) {
                    $name = $file->getClientOriginalName();
                    $data[] = [
                        'name' => $name,
                        'path' => 'uploads/tickets/' . $ticket->ticket_id . '/' . $name
                    ];
                    multi_upload_file($file, 'reply_attachments', $name, 'tickets/' . $ticket->ticket_id);
                }
            }
            $post['attachments'] = json_encode($data);
            $conversion = Conversion::create($post);

            event(new ReplyTicket($request,$conversion, $ticket));


            // Send Email to User


            if (!empty(company_setting('New Ticket Reply')) && company_setting('New Ticket Reply')  == true) {
                $user        = User::where('id', $ticket->created_by)->where('workspace_id', '=',  getActiveWorkSpace())->first();

                $uArr = [
                    'ticket_name' => $ticket->name,
                    'ticket_id' => $ticket->ticket_id,
                    'email' => $ticket->email,
                    'reply_description' => $request->reply_description,

                ];


                $resp = EmailTemplate::sendEmailTemplate('New Ticket Reply', [$ticket->email], $uArr);
            }

            return redirect()->back()->with('success', __('Reply added successfully') . ((isset($error_msg)) ? '<br> <span class="text-danger">' . $error_msg . '</span>' : ''));
        } else {
            return view('403');
        }
    }
}
