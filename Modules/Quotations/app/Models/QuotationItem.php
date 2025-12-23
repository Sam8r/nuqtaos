<?php

namespace Modules\Quotations\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Products\Models\Product;

// use Modules\Quotations\Database\Factories\QuotationItemFactory;

class QuotationItem extends Model
{
    use HasFactory, SoftDeletes;

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
        'quotation_id',
        'product_id',
    ];

    /**
     * Get the quotation that owns the item.
     */
    public function quotation()
    {
        return $this->belongsTo(Quotation::class);
    }

    /**
     * Get the product associated with the item.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    // protected static function newFactory(): QuotationItemFactory
    // {
    //     // return QuotationItemFactory::new();
    // }
}
