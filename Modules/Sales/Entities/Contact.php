<?php

namespace Modules\Sales\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'account',
        'email',
        'phone',
        'contact_address',
        'contact_city',
        'contact_state',
        'contact_country',
        'contact_postalcode',
        'description',
        'workspace',
        'created_by',
    ];

    protected static function newFactory()
    {
        return \Modules\Sales\Database\factories\ContactFactory::new();
    }
    protected $appends = ['account_name'];
    public function assign_user()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }

    public function assign_account()
    {
        return $this->hasOne('Modules\Sales\Entities\SalesAccount', 'id', 'account');
    }

    public function getAccountNameAttribute()
    {
        $account = Contact::find($this->account);

        return $this->attributes['account_name'] = !empty($account) ? $account->name : '';
    }
}
