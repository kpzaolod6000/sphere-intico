<?php

namespace Modules\SupportTicket\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Faq extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'description','workspace_id','created_by'
    ];

    protected static function newFactory()
    {
        return \Modules\SupportTicket\Database\factories\FaqFactory::new();
    }
}
