<?php

namespace Modules\Sales\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SalesInvoicePayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'client_id',
        'invoice_id',
        'amount',
        'date',
        'payment_type',
        'notes',
    ];

    protected static function newFactory()
    {
        return \Modules\Sales\Database\factories\SalesInvoicePaymentFactory::new();
    }
}
