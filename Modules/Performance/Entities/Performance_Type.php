<?php

namespace Modules\Performance\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Performance_Type extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'workspace',
        'created_by',
    ];
    
    protected static function newFactory()
    {
        return \Modules\Performance\Database\factories\PerformanceTypeFactory::new();
    }
    public function types()
    {
        return $this->hasMany(Competencies::class, 'type', 'id');
    }
}
