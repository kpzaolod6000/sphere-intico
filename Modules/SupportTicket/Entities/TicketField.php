<?php

namespace Modules\SupportTicket\Entities;

use App\Models\User;
use App\Models\WorkSpace;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TicketField extends Model
{
    use HasFactory;

    protected $fillable = [ 'name', 'type', 'placeholder', 'width', 'order', 'status', 'created_by', 'workspace_id',];

    protected static function newFactory()
    {
        return \Modules\SupportTicket\Database\factories\TicketFieldFactory::new();
    }
    public static $fieldTypes = [
        'text' => 'Text',
        'email' => 'Email',
        'number' => 'Number',
        'date' => 'Date',
        'textarea' => 'Textarea',
        'file' => 'File',
        'select' => 'Select',
    ];
    static function defultadd($company_id=null,$workspace_id=null)
    {
         // Defult Fields
         $field_array = [
            0=>[
                'name'=>'Name',
                'type'=>'text',
                'placeholder'=>'Enter Name',
                'width'=>'6',
                'order'=>0,
                'custom_id' => '1',
            ],
            1=>[
                'name'=>'Email',
                'type'=>'email',
                'placeholder'=>'Enter Email',
                'width'=>'6',
                'order'=>1,
                'custom_id' => '2',
            ],
            2=>[
                'name'=>'Category',
                'type'=>'text',
                'placeholder'=>'Select Category',
                'width'=>'6',
                'order'=>2,
                'custom_id' => '3',
            ],
            3=>[
                'name'=>'Subject',
                'type'=>'text',
                'placeholder'=>'Enter Subject',
                'width'=>'6',
                'order'=>3,
                'custom_id' => '4',
            ],
            4=>[
                'name'=>'Description',
                'type'=>'textarea',
                'placeholder'=>'Enter Description',
                'width'=>'12',
                'order'=>4,
                'custom_id' => '5',
            ],
            5=>[
                'name'=>'Attachments',
                'type'=>'file',
                'placeholder'=>'You can select multiple files',
                'width'=>'12',
                'order'=>5,
                'custom_id' => '6',
            ]
        ];
        if($company_id == Null)
        {
            $companys = User::where('type','company')->get();
            foreach($companys as $company)
            {
                $WorkSpaces = WorkSpace::where('created_by',$company->id)->get();
                foreach($WorkSpaces as $WorkSpace)
                {
                    $ticket_f = TicketField::where('created_by',$company->id)->where('workspace_id',$WorkSpace->id)->get();
                    if(count($ticket_f) == 0)
                    {
                        foreach($field_array as $field){
                            $new = new TicketField();
                            $new->name = $field['name'];
                            $new->type = $field['type'];
                            $new->placeholder = $field['placeholder'];
                            $new->width = $field['width'];
                            $new->order = $field['order'];
                            $new->custom_id = $field['custom_id'];
                            $new->status = 1;
                            $new->created_by = !empty($company->id) ? $company->id : 2;
                            $new->workspace_id = !empty($WorkSpace->id) ? $WorkSpace->id : 0 ;
                            $new->save();
                        }
                    }
                }
            }

        }
        elseif($workspace_id == Null)
        {
            $company = User::where('type','company')->where('id',$company_id)->first();
            $WorkSpaces = WorkSpace::where('created_by',$company->id)->get();
            foreach($WorkSpaces as $WorkSpace)
            {
                $ticket_f = TicketField::where('created_by',$company->id)->where('workspace_id',$WorkSpace->id)->get();

                if(count($ticket_f) == 0)
                {
                    foreach($field_array as $field){
                        $new = new TicketField();
                        $new->name = $field['name'];
                        $new->type = $field['type'];
                        $new->placeholder = $field['placeholder'];
                        $new->width = $field['width'];
                        $new->order = $field['order'];
                        $new->custom_id = $field['custom_id'];
                        $new->status = 1;
                        $new->created_by = !empty($company->id) ? $company->id : 2;
                        $new->workspace_id = !empty($WorkSpace->id) ? $WorkSpace->id : 0 ;
                        $new->save();
                    }
                }

            }
        }
        else
        {
            $company = User::where('type','company')->where('id',$company_id)->first();
            $WorkSpace = WorkSpace::where('created_by',$company->id)->where('id',$workspace_id)->first();
            $ticket_f = TicketField::where('created_by',$company->id)->where('workspace_id',$WorkSpace->id)->get();
            if(count($ticket_f) == 0)
            {
                foreach($field_array as $field){
                    $new = new TicketField();
                    $new->name = $field['name'];
                    $new->type = $field['type'];
                    $new->placeholder = $field['placeholder'];
                    $new->width = $field['width'];
                    $new->order = $field['order'];
                    $new->custom_id = $field['custom_id'];
                    $new->status = 1;
                    $new->created_by = !empty($company->id) ? $company->id : 2;
                    $new->workspace_id = !empty($WorkSpace->id) ? $WorkSpace->id : 0 ;
                    $new->save();
                }
            }
        }
    }



    public static function saveData($obj, $data)
    {
        if(!empty($data) && count($data) > 0)
        {
            $RecordId = $obj->id;
            foreach($data as $fieldId => $value)
            {
                if(!empty($fieldId) && !empty($value))
                {
                    \DB::insert(
                        'insert into ticket_field_values (`record_id`, `field_id`,`value`,`created_at`,`updated_at`) values (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`),`updated_at` = VALUES(`updated_at`) ', [
                                                                                                                                                                                                                                       $RecordId,
                                                                                                                                                                                                                                       $fieldId,
                                                                                                                                                                                                                                       $value,
                                                                                                                                                                                                                                       date('Y-m-d H:i:s'),
                                                                                                                                                                                                                                       date('Y-m-d H:i:s'),
                                                                                                                                                                                                                                   ]
                    );
                }
            }
        }

    }
    public static function getData($obj)
    {
        return \DB::table('ticket_field_values')->select(
            [
                'ticket_field_values.value',
                'ticket_fields.id',
            ]
        )->join('ticket_fields', 'ticket_field_values.field_id', '=', 'ticket_fields.id')->where('record_id', '=', $obj->id)->get()->pluck('value', 'id');
    }

}
