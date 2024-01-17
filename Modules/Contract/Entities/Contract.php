<?php

namespace Modules\Contract\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Contract extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject',
        'user_id',
        'project_id',
        'value',
        'type',
        'start_date',
        'end_date',
        'notes',
        'description',
        'client_signature',
        'owner_signature',
        'created_by',
        'status',
        'workspace',
    ];

    protected static function newFactory()
    {
        return \Modules\Contract\Database\factories\ContractFactory::new();
    }

    public function users()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }

    public function project()
    {
        return $this->hasOne('Modules\Taskly\Entities\Project', 'id', 'project_id');
    }

    public function contract_type()
    {
        return $this->hasOne('Modules\Contract\Entities\ContractType', 'id', 'type');
    }

    public function ContractAttechment()
    {
        return $this->belongsTo(ContractAttechment::class, 'id', 'contract_id');
    }

    public function ContractComment()
    {
        return $this->belongsTo('Modules\Contract\Entities\ContractComment', 'id', 'contract_id');
    }

    public function ContractNote()
    {
        return $this->belongsTo('Modules\Contract\Entities\ContractNote', 'id', 'contract_id');
    }
    public function files()
    {
        return $this->hasMany(ContractAttechment::class, 'contract_id' , 'id');
    }

    public function comment()
    {
        return $this->hasMany(ContractComment::class, 'contract_id', 'id');
    }
    public function note()
    {
        return $this->hasMany(ContractNote::class, 'contract_id', 'id');
    }

    public static function getContractSummary($contracts)
    {
        $total = 0;

        foreach($contracts as $contract)
        {
            $total += $contract->value;
        }
        return currency_format_with_sym($total);
    }

    public static function contractNumberFormat($number,$company_id = null)
    {
        if(!empty($company_id)){
            $data = !empty(company_setting('contract_prefix',$company_id)) ? company_setting('contract_prefix',$company_id) : '#CON';
        }
        else{
            $data = !empty(company_setting('contract_prefix')) ? company_setting('contract_prefix') : '#CON';
        }
        return $data. sprintf("%05d", $number);
    }

    public static function status()
    {
        $status = [
            'accept' => 'Accept',
            'decline' => 'Decline',
            'closed' => 'Close',
        ];
        return $status;
    }
}
