<?php

namespace Modules\Sales\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Meeting extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'status',
        'start_date',
        'end_date',
        'parent',
        'parent_id',
        'account',
        'description',
        'attendees_user',
        'attendees_contact',
        'attendees_lead',
        'workspace',
    ];

    public static $status   = [
        'Planned',
        'Held',
        'Not Held',
    ];
    public static $parent   = [
        'account' => 'Account',
        // 'lead' => 'Lead',
        'contact' => 'Contact',
        'opportunities' => 'Opportunities',
        'case' => 'Case',
    ];

    public function assign_user()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }

    public function attendees_contacts()
    {
        return $this->hasOne('Modules\Sales\Entities\Contact', 'id', 'attendees_contact');
    }
    public function attendees_users()
    {
        return $this->hasOne('App\Models\User', 'id', 'attendees_user');
    }

    public function getparent($type, $id)
    {
        if($type == 'account')
        {
            $parent = SalesAccount::find($id)->name;

        }
        elseif($type == 'contact')
        {
            $parent = Contact::find($id)->name;
        }
        elseif($type == 'opportunities')
        {
            $parent = Opportunities::find($id)->name;
        }
        elseif($type == 'case')
        {
            $parent = CommonCase::find($id)->name;
        }else{
            $parent= '';
        }

        return $parent;
    }

    protected static function newFactory()
    {
        return \Modules\Sales\Database\factories\MeetingFactory::new();
    }

    public function attendees_leads()
    {
        if(module_is_active('Lead'))
        {
            return $this->hasOne(\Modules\Lead\Entities\Lead::class, 'id', 'attendees_lead')->first();
        }
        else
        {
            return [];
        }
    }
}
