<?php

namespace Modules\Performance\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Competencies extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'workspace',
        'created_by',
    ];
    
    protected static function newFactory()
    {
        return \Modules\Performance\Database\factories\CompetenciesFactory::new();
    }
    public function getPerformance_type()
    {
        return $this->hasOne(Performance_Type::class, 'id', 'type');
    }
}
