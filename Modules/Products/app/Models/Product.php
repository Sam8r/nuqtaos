<?php

namespace Modules\Products\Models;

use App\Traits\Search\HasUniversalSearch;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Categories\Models\Category;
use Spatie\Translatable\HasTranslations;

// use Modules\Products\Database\Factories\ProductFactory;

class Product extends Model
{
    use HasFactory, SoftDeletes, HasTranslations, HasUniversalSearch;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'code',
        'name',
        'description',
        'price',
        'type',
        'unit',
        'sku',
        'qr_value',
        'images',
        'status',
        'category_id',
    ];

    protected $casts = [
        'images' => 'array',
    ];

    /**
     * The attributes that are translatable.
     */
    public $translatable = [
        'name',
        'description',
    ];

    protected function getCustomExcludedColumns(): array
    {
        return ['category_id, images'];
    }

    /**
     * Get the category that owns the product.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // protected static function newFactory(): ProductFactory
    // {
    //     // return ProductFactory::new();
    // }
}
