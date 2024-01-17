<?php

namespace Modules\Workflow\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WorkflowModule extends Model
{
    use HasFactory;

    protected $table = 'workflow_module';
    protected $fillable = [
        'module',
        'submodule'
    ];
    
    protected static function newFactory()
    {
        return \Modules\Workflow\Database\factories\WorkflowModuleFactory::new();
    }
}
