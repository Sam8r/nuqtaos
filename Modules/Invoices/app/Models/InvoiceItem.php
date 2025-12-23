<?php

namespace Modules\Invoices\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Products\Models\Product;

// use Modules\Invoices\Database\Factories\InvoiceItemFactory;

class InvoiceItem extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'custom_name',
        'description',
        'quantity',
        'unit_price',
        'discount_value',
        'discount_percent',
        'total_price',
        'invoice_id',
        'product_id',
    ];

    /**
     * Get the invoice that owns the item.
     */
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    /**
     * Get the product associated with the item.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    // protected static function newFactory(): InvoiceItemFactory
    // {
    //     // return InvoiceItemFactory::new();
    // }
}
