<?php

namespace Modules\SupportTicket\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Conversion extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id','description', 'attachments', 'sender'
    ];

    public function replyBy(){
        if($this->sender=='user'){
            return $this->ticket;
        }
        else{
            return $this->hasOne('App\Models\User','id','sender')->first();
        }
    }

    public function ticket(){
        return $this->hasOne('Modules\SupportTicket\Entities\Ticket','id','ticket_id');
    }

    protected static function newFactory()
    {
        return \Modules\SupportTicket\Database\factories\ConversionFactory::new();
    }
}
