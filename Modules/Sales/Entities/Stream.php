<?php

namespace Modules\Sales\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Stream extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'log_type',
        'file_upload',
        'remark',
        'workspace',
        'created_by',
    ];

    protected static function newFactory()
    {
        return \Modules\Sales\Database\factories\StreamFactory::new();
    }
}
