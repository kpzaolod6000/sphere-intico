<?php

namespace Modules\Workflow\Entities;

use App\Models\EmailTemplate;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Mail\CommonEmailTemplate;
use Illuminate\Support\Facades\Mail;
use Workflow\Rest\Client;


class SendMsg extends Model
{
    use HasFactory;

    protected $fillable = [];
    
   
    public static function SendWorkflowMsgs($mailTo,$content,$user_id=null,$workspace_id=null)
    { 
        if(!empty($user_id)){
            $usr = User::where('id',$user_id)->first();
        }else{
            $usr = \Auth::user();
        }
        //Remove Current Login user Email don't send mail to them

        $mailTo = $mailTo->email_address;
        $content = (object)$content;
        
        if(isset($mailTo) && !empty($mailTo))
        {
            if(!empty($content))
            {
                // send email
                if(!empty(company_setting('mail_from_address',$user_id,$workspace_id)))
                {
                    if(!empty($user_id)&& empty($workspace_id))
                    {
                        $setconfing =  SetConfigEmail($user_id);
                    }elseif(!empty($user_id)&& !empty($workspace_id))
                    {
                        $setconfing =  SetConfigEmail($user_id,$workspace_id);
                    }else{
                        $setconfing =  SetConfigEmail();
                    }
                    if($setconfing ==  true)
                    {
                        try
                        {
                            Mail::to($mailTo)->send(new CommonEmailTemplate($content,$user_id,$workspace_id));
                        }
                        catch(\Exception $e)
                        {
                            $error = $e->getMessage();
                        }
                    }
                    else
                    {
                    $error = __('Something went wrong please try again ');
                    }
                }
                else
                {
                    $error = __('E-Mail has been not sent due to SMTP configuration');
                }

                if(isset($error))
                {
                    $arReturn = [
                        'is_success' => false,
                        'error' => $error,
                    ];
                }
                else
                {
                    $arReturn = [
                        'is_success' => true,
                        'error' => false,
                    ];
                }
            }
            else
            {
                $arReturn = [
                    'is_success' => false,
                    'error' => __('Mail not send, email is empty'),
                ];
            }
            return $arReturn;

        }
        else
        {
            return [
                'is_success' => false,
                'error' => __('Mail not send, email not found'),
            ];
        }
    }

    protected static function newFactory()
    {
        return \Modules\Workflow\Database\factories\SendMsgFactory::new();
    }
}
