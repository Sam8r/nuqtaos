<?php

namespace Modules\Invoices\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Invoices\Database\Factories\InvoicePaymentFactory;

class InvoicePayment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'amount',
        'payment_date',
        'payment_method',
        'notes',
        'invoice_id',
    ];

    /**
     * Get the invoice that owns the payment.
     */
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    // protected static function newFactory(): InvoicePaymentFactory
    // {
    //     // return InvoicePaymentFactory::new();
    // }
}
