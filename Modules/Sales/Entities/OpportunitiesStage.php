<?php

namespace Modules\Sales\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OpportunitiesStage extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'workspace',
        'created_by',
    ];

    protected static function newFactory()
    {
        return \Modules\Sales\Database\factories\OpportunitiesStageFactory::new();
    }

    public function opportunity($id)
    {
        return Opportunities::where('stage', '=', $id)->get();
    }
}
