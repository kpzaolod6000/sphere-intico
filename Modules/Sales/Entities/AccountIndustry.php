<?php

namespace Modules\Sales\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AccountIndustry extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'workspace',
        'created_by',
    ];

    protected static function newFactory()
    {
        return \Modules\Sales\Database\factories\AccountIndustryFactory::new();
    }
}
