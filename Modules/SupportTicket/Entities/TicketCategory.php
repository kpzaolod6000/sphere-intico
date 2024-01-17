<?php

namespace Modules\SupportTicket\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TicketCategory extends Model
{
    use HasFactory;

    protected $fillable = [
            'name', 'color','workspace_id','created_by'
    ];

    protected static function newFactory()
    {
        return \Modules\SupportTicket\Database\factories\TicketCategoryFactory::new();
    }
}
