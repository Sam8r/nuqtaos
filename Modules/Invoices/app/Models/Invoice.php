<?php

namespace Modules\Invoices\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Clients\Models\Client;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

// use Modules\Invoices\Database\Factories\InvoiceFactory;

class Invoice extends Model
{
    use HasFactory, LogsActivity;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'number',
        'issue_date',
        'due_date',
        'status',
        'payment_terms',
        'late_payment_penalty_percent',
        'subtotal',
        'discount_total',
        'tax_total',
        'total',
        'discount_value',
        'discount_percent',
        'tax_value',
        'tax_percent',
        'created_by',
        'client_id',
        'quotation_id',
    ];

    protected static $recordEvents = ['updated'];

    protected static $logExcept = ['updated_at'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(fn(string $eventName) => "Invoice has been edited");
    }

    public function activities()
    {
        return $this->morphMany(Activity::class, 'subject')->latest();
    }

    /**
     * Get the client that owns the invoice.
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Get the invoice items for the invoice.
     */
    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    /**
     * Get the payments for the invoice.
     */
    public function payments()
    {
        return $this->hasMany(InvoicePayment::class);
    }

    /**
     * Accessor for computed status attribute.
     */
    public function getComputedStatusAttribute()
    {
        $total = (float) $this->total;
        $paid = (float) $this->paid_amount;
        $outstanding = (float) $this->outstanding_balance;
        $dueDate = $this->due_date ? Carbon::parse($this->due_date) : null;

        // If total is missing → Draft
        if ($total <= 0) {
            return 'Draft';
        }

        // Full Paid
        if ($paid >= $total) {
            return 'Paid';
        }

        // Partially Paid
        if ($paid > 0 && $paid < $total) {
            // Check if overdue
            if ($dueDate && $dueDate->isPast() && $outstanding > 0) {
                return 'Overdue';
            }

            return 'Partially Paid';
        }

        // If unpaid and total > 0
        if ($paid == 0 && $total > 0) {
            // Overdue?
            if ($dueDate && $dueDate->isPast() && $outstanding > 0) {
                return 'Overdue';
            }

            return 'Pending';
        }

        return 'Draft';
    }

    // protected static function newFactory(): InvoiceFactory
    // {
    //     // return InvoiceFactory::new();
    // }
}
