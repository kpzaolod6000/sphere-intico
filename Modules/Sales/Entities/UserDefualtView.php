<?php

namespace Modules\Sales\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserDefualtView extends Model
{
    use HasFactory;

    protected $fillable = [
        'module',
        'route',
        'view',
        'workspace',
        'user_id',
    ];

    protected static function newFactory()
    {
        return \Modules\Sales\Database\factories\UserDefualtViewFactory::new();
    }
}
