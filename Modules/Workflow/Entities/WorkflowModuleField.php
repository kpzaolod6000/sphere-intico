<?php

namespace Modules\Workflow\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WorkflowModuleField extends Model
{
    use HasFactory;

    protected $fillable = [
        'workmodule_id',
        'field_name',
        'input_type',
        'model_name',
    ];
    
    protected static function newFactory()
    {
        return \Modules\Workflow\Database\factories\WorkflowModuleFieldFactory::new();
    }
}
