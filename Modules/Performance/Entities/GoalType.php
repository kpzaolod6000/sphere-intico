<?php

namespace Modules\Performance\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GoalType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'workspace',
        'created_by',
    ];
    
    protected static function newFactory()
    {
        return \Modules\Performance\Database\factories\GoalTypeFactory::new();
    }
}
