<?php

namespace Modules\Sales\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SalesAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'document_id',
        'name',
        'email',
        'phone',
        'website',
        'billing_address',
        'billing_city',
        'billing_state',
        'billing_country',
        'billing_postalcode',
        'shipping_address',
        'shipping_city',
        'shipping_state',
        'shipping_country',
        'shipping_postalcode',
        'type',
        'industry',
        'description',
        'created_by',
        'workspace',
    ];

    protected static function newFactory()
    {
        return \Modules\Sales\Database\factories\SalesAccountFactory::new();
    }

    public function assign_user()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }

    public function accountType()
    {
        return $this->hasOne('Modules\Sales\Entities\SalesAccountType', 'id', 'type');
    }

    public function accountIndustry()
    {
        return $this->hasOne('Modules\Sales\Entities\AccountIndustry', 'id', 'industry');
    }

    public function getTypeNameAttribute()
    {
        $type = SalesAccount::find($this->type);

        return $this->attributes['type_name'] = !empty($type) ? $type->name : '';
    }

    public function getIndustryNameAttribute()
    {
        $type = SalesAccount::find($this->industry);

        return $this->attributes['industry_name'] = !empty($type) ? $type->name : '';
    }
}
