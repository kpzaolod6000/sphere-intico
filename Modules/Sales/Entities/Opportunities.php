<?php

namespace Modules\Sales\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Opportunities extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'account_name',
        'stage',
        'amount',
        'probability',
        'close_date',
        'contacts',
        'lead_source',
        'description',
        'workspace',
        'created_by',
    ];

    protected static function newFactory()
    {
        return \Modules\Sales\Database\factories\OpportunitiesFactory::new();
    }
    public function assign_user()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }

    public function stages()
    {
        return $this->hasOne('Modules\Sales\Entities\OpportunitiesStage', 'id', 'stage');
    }

    public function accounts()
    {
        return $this->hasOne('Modules\Sales\Entities\SalesAccount', 'id', 'account');
    }

    public function leadsource()
    {
        return $this->hasOne('Modules\Lead\Entities\Source', 'id', 'lead_source');
    }
}
