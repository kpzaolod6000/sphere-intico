<?php

namespace Modules\Workflow\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Workflow\Entities\Workflowdothis;
use Modules\Workflow\Entities\WorkflowModule;
use Illuminate\Support\Facades\Validator;
use Modules\Hrm\Entities\Award;
use Modules\Workflow\Entities\Workflow;
use Modules\Workflow\Entities\WorkflowModuleField;

class WorkflowController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {  
        if (Auth::user()->can('workflow manage')) {
            if (!empty($request->workflow)) 
            {
                $workflows = Workflow::where('created_by', '=', creatorId())->where('workspace', '=', getActiveWorkSpace())->where('id', $request->workflow)->get();
            }else{
                $workflows = Workflow::where('created_by', '=', creatorId())->where('workspace', '=', getActiveWorkSpace())->get();
            }
            return view('workflow::workflow.index', compact('workflows'));
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
        if (\Auth::user()->can('workflow create')) {

            $modules_n = WorkflowModule::groupBy('module')->get();
            $modules[''] = 'Please select';

            foreach($modules_n as $module)
            {
               if(module_is_active($module->module) || $module->module == 'general'){
                   
                    $modules[$module->id] = Module_Alias_Name($module->module);

                }
            } 
            $workflowdothis = Workflowdothis::all()->pluck('submodule','id');
            return view('workflow::workflow.create', compact('modules','workflowdothis'));
        } else {
            return response()->json(['error' => __('Permission Denied.')], 401);
        }
    }
    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {   
        if (Auth::user()->can('workflow create')) {
            $validatorArray = [
                'name' => 'required|max:120',
                'event' => 'required',
                'do_this' => 'required',
                'module_name' => 'required',
            ];
            $validator = Validator::make(
                $request->all(),
                $validatorArray
            );
            if ($validator->fails()) {
                return redirect()->back()->with('error', $validator->errors()->first());
            }

            $Workflow              = new Workflow();
            $Workflow->name        = $request->name;
            $Workflow->event       = $request->event;
            $Workflow->module_name  = $request->module_name;
            $Workflow->do_this    = implode(",",$request->do_this); 
            $Workflow->created_by  = creatorId();
            $Workflow->workspace   = getActiveWorkSpace();
            $Workflow->save();

            return redirect()->route('workflow.edit', $Workflow->id)->with('success', __('User successfully created.'));
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
        return view('workflow::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        if (\Auth::user()->can('workflow edit')) 
        {
            $methods = ['GET' => 'GET', 'POST' => 'POST'];
            $workflow = Workflow::find($id);

            if($workflow)
            {
                 
                $modules_n = WorkflowModule::groupBy('module')->get();
                foreach($modules_n as $module)
                {
                   if(module_is_active($module->module) || $module->module == 'general'){
                       $modules[$module->id] = Module_Alias_Name($module->module);
                    }
                }
                $workflowdothis = Workflowdothis::all()->pluck('submodule','id');
            
                return view('workflow::workflow.edit', compact('modules', 'workflow', 'methods','workflowdothis'));
            }else{
                return redirect()->route('workflow.index')->with('error', __('Workflow not found.'));
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
        if (\Auth::user()->can('workflow edit')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'name' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $workflow = Workflow::find($id);
            $workflow->name = $request->name;
            $workflow->event = $request->event;
            $workflow->module_name = $request->module_name;
            $workflow->do_this = implode(",",$request->do_this);
            $workflow->message = $request->message; 
            $workflow->workspace = getActiveWorkSpace();

            $json_dothis = [];
            if(!empty($request->email_type))
            {
                $email = [
                    'email_type' => $request->email_type,
                    'email_address' => ($request->email_type == 'staff') ? $request->email_staff:  $request->email_address,
                ];
                $json_dothis['email'] = $email;
            }  

            if(!empty($request->webhook_url) && !empty($request->method))
            {
                $webhook = [
                    'webhook_url' => $request->webhook_url,
                    'method' => $request->method,
                ];
                $json_dothis['webhook'] = $webhook;
            }  
            
            if(!empty($request->telegram_access) && !empty($request->telegram_chat))
            {
                $telegram = [
                    'telegram_access' => $request->telegram_access,
                    'telegram_chat' => $request->telegram_chat,
                ];

                $json_dothis['telegram'] = $telegram;
            }   
           
            if(!empty($request->slack_url))
            {
                $slack = [
                    'slack_url' => $request->slack_url,
                ];
                $json_dothis['slack'] = $slack;
            }  

            if(!empty($request->twilio_type))
            {
                $twilio = [
                    'twilio_type' => $request->twilio_type,
                    'twilio_number' => ($request->twilio_type == 'staff') ? $request->twilio_staff:  $request->twilio_number,
                ];
                $json_dothis['twilio'] = $twilio;
            } 
            $fieldsArray = [];
              
            if(isset($request->fields) && count($request->fields) > 0)
            {
                foreach ($request->fields as $key => $value) {                    
                    $fieldsArray[] = [
                        'preview_type'  => array_key_exists("preview_type",$value) ?  $value['preview_type'] : null,
                        'condition'     => array_key_exists("condition",$value) ?  $value['condition'] : null,
                        'value'         => array_key_exists("value",$value) ?  $value['value'] : null,
                    ];
                }
            }  
            
            $workflow->do_this_data = json_encode($json_dothis); 
            $workflow->json_data = json_encode($fieldsArray);
            $workflow->created_by = creatorId();
            $workflow->update();

            return redirect()->back()->with('success', __('Workflow successfully Updated!'));
        } else{
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy(Workflow $workflow)
    {
        if (\Auth::user()->can('workflow delete')) {
            $workflow->delete();
            return redirect()->back()->with('success', __('workflow successfully deleted!'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function getfielddata(Request $request)
    { 
        $events = WorkflowModuleField::where('workmodule_id', $request->event_id)->get()->pluck('field_name','id');
        
        $response = [
            'is_success' => true,
            'message' => '',
            'data' => $events,
        ]; 
        return response()->json($response);
    }

    public function getcondition(Request $request)
    { 
         
        $workflow = WorkflowModuleField::find($request->workmodule_id);
        $data = null;
        if($workflow->input_type == 'select')
        {
            if($workflow->model_name == 'Tax')
            {
                $data = \Modules\ProductService\Entities\Tax::where('workspace_id', getActiveWorkSpace())->get()->pluck('name', 'id');
            }
            elseif ($workflow->model_name == 'Category')
            {
                $data = \Modules\ProductService\Entities\Category::where('workspace_id', getActiveWorkSpace())->where('type', '=', 1)->get()->pluck('name', 'id');
            }
            elseif ($workflow->model_name == 'User')
            {
                $data = User::where('created_by', '=', creatorId())->where('type', '!=', 'client')->where('workspace_id', getActiveWorkSpace())->get()->pluck('name', 'id');
            }
            elseif ($workflow->model_name == 'ContractType')
            {
                $data = \Modules\Contract\Entities\ContractType::where('created_by', '=', creatorId())->where('workspace',getActiveWorkSpace())->get()->pluck('name', 'id');
            }
            elseif ($workflow->model_name == 'Ticket')
            { 
                $data = \Modules\SupportTicket\Entities\Ticket::where('created_by', '=', creatorId())->where('workspace_id',getActiveWorkSpace())->get()->pluck('status', 'id');
            }
            elseif ($workflow->model_name == 'TicketCategory')
            {  
                $data = \Modules\SupportTicket\Entities\TicketCategory::where('created_by', '=', creatorId())->where('workspace_id',getActiveWorkSpace())->get()->pluck('name', 'id');
            }
            elseif ($workflow->model_name == 'DealUser')
            {  
                $data = User::where('created_by', '=', creatorId())->where('type', '=', 'client')->where('workspace_id',getActiveWorkSpace())->get()->pluck('name', 'id');
            }
            elseif ($workflow->model_name == 'Appointment')
            {  
                $data = \Modules\Appointment\Entities\Appointment::$appointment_type;
            }
            elseif ($workflow->model_name == 'pos_Category')
            {  
                $data = \Modules\ProductService\Entities\Category::where('created_by', creatorId())->where('workspace_id',getActiveWorkSpace())->where('type', 2)->get()->pluck('name', 'id');
            }
            elseif ($workflow->model_name == 'Warehouse')
            {  
                $data = \Modules\Pos\Entities\Warehouse::where('created_by', creatorId())->where('workspace',getActiveWorkSpace())->get()->pluck('name', 'id');
            }
            elseif ($workflow->model_name == 'AwardType')
            {  
                $data = \Modules\Hrm\Entities\AwardType::where('created_by', creatorId())->where('workspace',getActiveWorkSpace())->get()->pluck('name', 'id');
            }
            elseif ($workflow->model_name == 'Award')
            {  
                $data = User::where('created_by', '=', creatorId())->where('type', '=', 'staff')->where('workspace_id', getActiveWorkSpace())->get()->pluck('name', 'id');
            }
            elseif ($workflow->model_name == 'Branch')
            {  
                $data = \Modules\Hrm\Entities\Branch::where('created_by', '=', creatorId())->where('workspace',getActiveWorkSpace())->get()->pluck('name', 'id');
            }
            elseif ($workflow->model_name == 'Training')
            {  
                $data = \Modules\Training\Entities\Training::$options;
            }
            elseif ($workflow->model_name == 'TrainingType')
            {  
                $data = \Modules\Training\Entities\TrainingType::where('created_by', '=', creatorId())->where('workspace',getActiveWorkSpace())->get()->pluck('name', 'id');
            }
            elseif ($workflow->model_name == 'Trainer')
            {  
                $data = \Modules\Training\Entities\Trainer::where('created_by', '=', creatorId())->where('workspace',getActiveWorkSpace())->get()->pluck('firstname', 'id');
            }
            elseif ($workflow->model_name == 'Employee')
            {  
                $data = \Modules\Hrm\Entities\Employee::where('created_by', '=', creatorId())->where('workspace',getActiveWorkSpace())->get()->pluck('name', 'id');
            }
            elseif ($workflow->model_name == 'Location')
            {  
                $data = \Modules\CMMS\Entities\Location::where('workspace',getActiveWorkSpace())->where('created_by',creatorId())->get()->pluck('name', 'id');
            }
            else
            {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        }
        $returnHTML =  view('workflow::workflow.input', compact('workflow','data','request'))->render();
 
            $response = [
                'is_success' => true,
                'message' => '',
                'html' => $returnHTML,
            ]; 
        return response()->json($response);
    }

    public function attribute(Request $request)
    {      
        $Workflowdothis = [];
        
        if(isset($request->attribute_id) && count($request->attribute_id) > 0)
        {
            $Workflowdothis = Workflowdothis::whereIn('id' , $request->attribute_id)->get()->pluck('submodule')->toArray();  
        }
        $workflow = Workflow::find($request->workflow_id);
        
        $staff = User::where('created_by',creatorId())->where('workspace_id',getActiveWorkSpace())->get()->pluck('name','email');
        $staff_mobile = User::where('created_by',creatorId())->where('workspace_id',getActiveWorkSpace())->get()->pluck('name','mobile_no');
        $methods = ['GET' => 'GET', 'POST' => 'POST'];
        $returnHTML =  view('workflow::workflow.append',compact('Workflowdothis','workflow','staff','staff_mobile','methods'))->render();

        $response = [
            'is_success' => true,
            'message' => '',
            'html' => $returnHTML,
        ]; 
        return response()->json($response);
    }

    public function module(Request $request)
    {        
        $workflowmodule = WorkflowModule::find($request->module);
     
        $response = [
            'is_success' => false,
            'message' => "_('')",
            'event_name' => [],
        ];
        if($workflowmodule)
        {
            $event_name = WorkflowModule::where('module', $workflowmodule->module)->pluck('submodule','id');
          
            $response = [
                'is_success' => true,
                'message' => '',
                'event_name' => $event_name,
            ];
             
        }
        return response()->json($response);
         
    }

}
