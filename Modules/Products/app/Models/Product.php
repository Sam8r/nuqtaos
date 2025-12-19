<?php

namespace Modules\Products\Models;

use App\Traits\Search\HasUniversalSearch;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Modules\Categories\Models\Category;
use Picqer\Barcode\BarcodeGeneratorPNG;
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
        'barcode_path',
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

    protected static function booted()
    {
        // Automatically generate and store barcode image on saving
        static::saving(function ($product) {
            if (!$product->barcode_path) {
                $generator = new BarcodeGeneratorPNG();
                $barcodeData = $generator->getBarcode(
                    $product->code,
                    $generator::TYPE_CODE_128
                );

                $fileName = 'barcodes/' . $product->code . '.png';
                Storage::disk('public')->put($fileName, $barcodeData);

                $product->barcode_path = $fileName;
            }
        });
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
