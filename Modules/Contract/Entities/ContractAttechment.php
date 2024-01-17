<?php

namespace Modules\Contract\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ContractAttechment extends Model
{
    use HasFactory;

    protected $fillable = [
        'contract_id',
        'user_id',
        'workspace',
        'files',
    ];

    protected static function newFactory()
    {
        return \Modules\Contract\Database\factories\ContractAttechmentFactory::new();
    }
}
