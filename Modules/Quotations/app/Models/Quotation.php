<?php

namespace Modules\Quotations\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use Modules\Clients\Models\Client;

// use Modules\Quotations\Database\Factories\QuotationFactory;

class Quotation extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'number',
        'issue_date',
        'valid_until',
        'status',
        'terms_and_conditions',
        'subtotal',
        'discount_total',
        'tax_total',
        'total',
        'client_id',
    ];

    /**
     * Get the client that owns the quotation.
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Get the items for the quotation.
     */
    public function items()
    {
        return $this->hasMany(QuotationItem::class);
    }

    /**
     * Accessor for computed status attribute.
     */
    public function getComputedStatusAttribute()
    {
        // If original status is rejected/approved → keep it
        if (in_array($this->status, ['approved', 'rejected'])) {
            return $this->status;
        }

        // If valid_until has passed → expired
        if ($this->valid_until && Carbon::parse($this->valid_until)->isPast()) {
            return 'Expired';
        }

        // Otherwise return DB status
        return $this->status ?? 'Draft';
    }

    // protected static function newFactory(): QuotationFactory
    // {
    //     // return QuotationFactory::new();
    // }
}
