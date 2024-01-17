<?php

namespace Modules\Sales\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\User;
use Modules\Sales\Entities\Contact;
use Modules\Sales\Entities\SalesAccount;
use Modules\Sales\Entities\Stream;
use Modules\Sales\Entities\Opportunities;
use Modules\Sales\Entities\UserDefualtView;
use Modules\Sales\Entities\SalesUtility;
use Illuminate\Support\Facades\Auth;
use Modules\Sales\Entities\Call;
use Modules\Sales\Entities\CommonCase;
use Modules\Sales\Entities\Meeting;
use Modules\Sales\Entities\Quote;
use Modules\Sales\Entities\SalesInvoice;
use Modules\Sales\Entities\SalesOrder;
use Modules\Sales\Events\CreateContact;
use Modules\Sales\Events\DestroyContact;
use Modules\Sales\Events\UpdateContact;

class ContactController extends Controller
{
   /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if(\Auth::user()->can('contact manage'))
        {
            $contacts = Contact::where('created_by', creatorId())->where('workspace',getActiveWorkSpace())->get();

            $defualtView         = new UserDefualtView();
            $defualtView->route  = \Request::route()->getName();
            $defualtView->module = 'contact';
            $defualtView->view   = 'list';
            SalesUtility::userDefualtView($defualtView);
            return view('sales::contact.index', compact('contacts'));
        }
        else
        {
            return redirect()->back()->with('error', 'permission Denied');
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create($type, $id)
    {
        if(\Auth::user()->can('contact create'))
        {
            $user    = Auth::user();
            $account = SalesAccount::where('created_by', creatorId())->where('workspace',getActiveWorkSpace())->get()->pluck('name', 'id');
            $account->prepend('--', 0);
            $user = User::where('workspace_id',getActiveWorkSpace())->emp()->get()->pluck('name', 'id');
            $user->prepend('--',0);

            return view('sales::contact.create', compact('user','account' ,'type', 'id'));
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
        if(\Auth::user()->can('contact create'))
        {
            $validator = \Validator::make(
                $request->all(), [
                                   'name' => 'required|max:120',
                                   'email' => 'required|email|unique:contacts',
                                   'contact_postalcode' => 'required',
                                   'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:9',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $contact                       = new Contact();
            $contact['user_id']            = $request->user_id;
            $contact['name']               = $request->name;
            $contact['account']            = $request->account;
            $contact['email']              = $request->email;
            $contact['phone']              = $request->phone;
            $contact['contact_address']    = $request->contact_address;
            $contact['contact_city']       = $request->contact_city;
            $contact['contact_state']      = $request->contact_state;
            $contact['contact_country']    = $request->contact_country;
            $contact['contact_postalcode'] = $request->contact_postalcode;
            $contact['description']        = $request->description;
            $contact['workspace']          = getActiveWorkSpace();
            $contact['created_by']         = creatorId();
            $contact->save();

            Stream::create(
                [
                    'user_id' => Auth::user()->id,
                    'created_by' => creatorId(),
                    'log_type' => 'created',
                    'remark' => json_encode(
                        [
                            'owner_name' => Auth::user()->username,
                            'title' => 'contact',
                            'stream_comment' => '',
                            'user_name' => $contact->name,
                        ]
                    ),
                ]
            );
            event(new CreateContact($request,$contact));

            return redirect()->back()->with('success', __('Contact Successfully Created.'));
        }
        else
        {
            return redirect()->back()->with('error', 'permission Denied');
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show(Contact $contact)
    {
        if(\Auth::user()->can('contact show'))
        {
            return view('sales::contact.view', compact('contact'));
        }
        else
        {
            return response()->json(['error' => __('Permission denied.')], 401);
        };
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit(Contact $contact)
    {
        if(\Auth::user()->can('contact edit'))
        {
            $user = User::where('workspace_id',getActiveWorkSpace())->emp()->get()->pluck('name', 'id');
            $user->prepend('--', 0);
            $account = SalesAccount::where('created_by', creatorId())->where('workspace',getActiveWorkSpace())->get()->pluck('name', 'id');
            $account->prepend('--', '');
            $opportunitiess = Opportunities::where('contact', $contact->id)->where('workspace',getActiveWorkSpace())->get();
            $parent         = 'contact';
            $log_type       = 'contact comment';
            $streams        = Stream::where('log_type', $log_type)->get();
            $quotes         = Quote::where('shipping_contact', $contact->id)->where('workspace',getActiveWorkSpace())->get();
            $salesinvoices  = SalesInvoice::where('shipping_contact', $contact->id)->where('workspace',getActiveWorkSpace())->get();
            $salesorders    = SalesOrder::where('shipping_contact', $contact->id)->where('workspace',getActiveWorkSpace())->get();
            $cases          = CommonCase::where('contact', $contact->id)->where('workspace',getActiveWorkSpace())->get();
            $calls          = Call::where('attendees_contact', $contact->id)->where('workspace',getActiveWorkSpace())->get();
            $meetings       = Meeting::where('attendees_contact', $contact->id)->where('workspace',getActiveWorkSpace())->get();

            // get previous user id
            $previous = Contact::where('id', '<', $contact->id)->max('id');
            // get next user id
            $next = Contact::where('id', '>', $contact->id)->min('id');

            return view('sales::contact.edit', compact('contact','quotes','salesorders','salesinvoices','cases','calls','meetings','opportunitiess','account','streams','user', 'previous', 'next'));
        }
        else
        {
            return redirect()->back()->with('error', 'permission Denied');
        }
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, Contact $contact)
    {
        if(\Auth::user()->can('contact edit'))
        {
            $validator = \Validator::make(
                $request->all(), [
                                   'name' => 'required|max:120',
                                   'email' => 'required|email|unique:users',
                                   'contact_postalcode' => 'required',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            $contact['user_id']            = $request->user;
            $contact['name']               = $request->name;
            $contact['account']            = $request->account;
            $contact['email']              = $request->email;
            $contact['phone']              = $request->phone;
            $contact['contact_address']    = $request->contact_address;
            $contact['contact_city']       = $request->contact_city;
            $contact['contact_state']      = $request->contact_state;
            $contact['contact_country']    = $request->contact_country;
            $contact['contact_postalcode'] = $request->contact_postalcode;
            $contact['description']        = $request->description;
            $contact['workspace']          = getActiveWorkSpace();
            $contact['created_by']         = creatorId();
            $contact->update();

            Stream::create(
                [
                    'user_id' => Auth::user()->id,
                    'created_by' => creatorId(),
                    'log_type' => 'updated',
                    'remark' => json_encode(
                        [
                            'owner_name' => Auth::user()->username,
                            'title' => 'contact',
                            'stream_comment' => '',
                            'user_name' => $contact->name,
                        ]
                    ),
                ]
            );
            event(new UpdateContact($request,$contact));

            return redirect()->back()->with('success', __('Contact Successfully Updated.'));
        }
        else
        {
            return redirect()->back()->with('error', 'permission Denied');
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy(Contact $contact)
    {
        if(\Auth::user()->can('contact delete'))
        {
            event(new DestroyContact($contact));

            $contact->delete();

            return redirect()->back()->with('success', __('Contact Successfully Deleted.'));
        }
        else
        {
            return redirect()->back()->with('error', 'permission Denied');
        }
    }


    public function grid()
    {
        if(\Auth::user()->can('contact manage'))
        {
            $contacts = Contact::where('created_by', creatorId())->where('workspace',getActiveWorkSpace())->get();

            $defualtView         = new UserDefualtView();
            $defualtView->route  = \Request::route()->getName();
            $defualtView->module = 'contact';
            $defualtView->view   = 'grid';
            SalesUtility::userDefualtView($defualtView);

            return view('sales::contact.grid', compact('contacts'));
        }
        else
        {
            return redirect()->back()->with('error', 'permission Denied');
        }
    }

    public function fileImportExport()
    {
        if(Auth::user()->can('contact import'))
        {
            return view('sales::contact.import');
        }
        else
        {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function fileImport(Request $request)
    {
        if(Auth::user()->can('contact import'))
        {
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
                                    <option value="email">Email</option>
                                    <option value="phone">Phone No</option>
                                    <option value="contact_address">Address</option>
                                    <option value="contact_city">City</option>
                                    <option value="contact_state">State</option>
                                    <option value="contact_postalcode">Postal Code</option>
                                    <option value="contact_country">Country</option>
                                    <option value="description">Description</option>
                                    </select>
                                </th>
                                ';
                    }

                    $html .= '
                                <th>
                                        <select name="set_column_data" class="form-control set_column_data account_name" data-column_number="' . $count+1 . '">
                                            <option value="account">Account</option>
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
                                    <select name="account" class="form-control account-value">;';
                                    $accounts = SalesAccount::where('created_by',creatorId())->where('workspace',getActiveWorkSpace())->pluck('name','id');
                                        foreach ($accounts as $key => $account)
                                        {
                                            $html .=' <option value="'.$key.'">'.$account.'</option>';
                                        }
                                    $html .='  </select>
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
        }
        else
        {
            return redirect()->back()->with('error', 'permission Denied');
        }

    }

    public function fileImportModal()
    {
        if(Auth::user()->can('contact import'))
        {
            return view('sales::contact.import_modal');
        }
        else
        {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function contactImportdata(Request $request)
    {
        if(Auth::user()->can('contact import'))
        {
            session_start();
            $html = '<h3 class="text-danger text-center">Below data is not inserted</h3></br>';
            $flag = 0;
            $html .= '<table class="table table-bordered"><tr>';
            $file_data = $_SESSION['file_data'];

            unset($_SESSION['file_data']);

            $user = Auth::user();

            foreach ($file_data as $key=>$row) {
                    $contact = Contact::where('created_by',creatorId())->where('workspace',getActiveWorkSpace())->Where('email', 'like',$row[$request->email])->get();

                if($contact->isEmpty()){
                    try {
                            $account = SalesAccount::find($request->account[$key]);
                        Contact::create([
                            'name' => $row[$request->name],
                            'email' => $row[$request->email],
                            'phone' => $row[$request->phone],
                            'contact_address' => $row[$request->contact_address],
                            'contact_city' => $row[$request->contact_city],
                            'contact_state' => $row[$request->contact_state],
                            'contact_country' => $row[$request->contact_country],
                            'contact_postalcode' => $row[$request->contact_postalcode],
                            'description' => $row[$request->description],
                            'account' => $account->id,
                            'created_by' => creatorId(),
                            'workspace' => getActiveWorkSpace(),
                        ]);
                    }
                    catch (\Exception $e)
                    {
                        $flag = 1;
                        $html .= '<tr>';

                        $html .= '<td>' . $row[$request->name] . '</td>';
                        $html .= '<td>' . $row[$request->email] . '</td>';
                        $html .= '<td>' . $row[$request->phone] . '</td>';
                        $html .= '<td>' . $row[$request->contact_address] . '</td>';
                        $html .= '<td>' . $row[$request->contact_city] . '</td>';
                        $html .= '<td>' . $row[$request->contact_state] . '</td>';
                        $html .= '<td>' . $row[$request->contact_country] . '</td>';
                        $html .= '<td>' . $row[$request->contact_postalcode] . '</td>';
                        $html .= '<td>' . $row[$request->description] . '</td>';

                        $html .= '</tr>';
                    }
                }
                else
                {
                    $flag = 1;
                    $html .= '<tr>';

                    $html .= '<td>' . $row[$request->name] . '</td>';
                    $html .= '<td>' . $row[$request->email] . '</td>';
                    $html .= '<td>' . $row[$request->phone] . '</td>';
                    $html .= '<td>' . $row[$request->contact_address] . '</td>';
                    $html .= '<td>' . $row[$request->contact_city] . '</td>';
                    $html .= '<td>' . $row[$request->contact_state] . '</td>';
                    $html .= '<td>' . $row[$request->contact_country] . '</td>';
                    $html .= '<td>' . $row[$request->contact_postalcode] . '</td>';
                    $html .= '<td>' . $row[$request->description] . '</td>';

                    $html .= '</tr>';
                }
            }

            $html .= '
                            </table>
                            <br />
                            ';
            if ($flag == 1)
            {

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
        }
        else
        {
            return redirect()->back()->with('error', 'permission Denied');
        }
    }
}
