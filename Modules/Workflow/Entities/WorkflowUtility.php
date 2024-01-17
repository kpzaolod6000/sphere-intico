<?php

namespace Modules\Workflow\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Workflow\Events\WorkflowSlackMsg;
use Modules\Workflow\Events\WorkflowTelegramMsg;
use Modules\Workflow\Events\WorkflowTwilioMsg;
use Modules\Workflow\Events\WorkflowWebhook;

class WorkflowUtility extends Model
{
    use HasFactory;

    protected $fillable = [];
    
    public static function call_do_this($workflow_id,$details = null)
    {  
        $workflow = Workflow::find($workflow_id);
        $idArray = explode(',', $workflow->do_this);  
        $workflowdothis = Workflowdothis::whereIn('id', $idArray)->where('type','company')->get();
        $do_this_data = json_decode($workflow->do_this_data);
         
        // remove html tags from the messages
        $msg = !empty($workflow->message) ? strip_tags($workflow->message) : 'Invoice created';
       
        foreach ($workflowdothis as $key => $workflowdothi) 
        {
    
            if($workflowdothi->submodule == 'Send Slack Notification')
            {  
                $setting = isset($do_this_data->slack) ? $do_this_data->slack : null;
                if($setting)
                {
                    event(new WorkflowSlackMsg($msg,$setting));
                }
            }
            else if($workflowdothi->submodule == 'Send Telegram Notification')  
            { 
                $setting = isset($do_this_data->telegram) ? $do_this_data->telegram : null;
                if($setting)
                {
                    event(new WorkflowTelegramMsg($msg,$setting));
                }
            }     
            else if($workflowdothi->submodule == 'Send Twilio Notification')  
            { 
                $setting = isset($do_this_data->twilio) ? $do_this_data->twilio : null;
                if($setting)
                {
                    event(new WorkflowTwilioMsg($msg,$setting));
                }
            }
            else if($workflowdothi->submodule == 'Send Webhook URL') 
            {
                $setting = isset($do_this_data->webhook) ? $do_this_data->webhook : null;
                if($setting)
                {
                    event(new WorkflowWebhook($setting,$details));
                }
            }
            else if($workflowdothi->submodule == 'Send Email Notification')
            {
                $mail_to = isset($do_this_data->email) ? $do_this_data->email : null;
                if(!empty($mail_to))
                {
                    $content = [
                        'from' => !empty(company_setting('company_name')) ? company_setting('company_name') : $workflow->name,
                        'subject' => $workflow->module->submodule,
                        'content'=> $workflow->message,
                    ];
                }
                
                SendMsg::SendWorkflowMsgs($mail_to, $content, $workflow->created_by, $workflow->workspace);
            } 
            else
            {
                break;
            }
        }
    }
    protected static function newFactory()
    {
        return \Modules\Workflow\Database\factories\WorkflowUtilityFactory::new();
    }
}
