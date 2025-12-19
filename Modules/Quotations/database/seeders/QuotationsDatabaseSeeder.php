<?php

namespace Modules\Quotations\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Modules\Clients\Models\Client;
use Modules\Products\Models\Product;
use Modules\Quotations\Models\Quotation;
use Modules\Quotations\Models\QuotationItem;

class QuotationsDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $client = Client::first();
        $product = Product::first();

        $number = 'QT-' . now()->format('Ymd') . '-0001';

        $quotation = Quotation::firstOrCreate(
            ['number' => $number],
            [
                'issue_date' => Carbon::now()->toDateString(),
                'valid_until' => Carbon::now()->addDays(30)->toDateString(),
                'status' => 'Draft',
                'terms_and_conditions' => null,
                'subtotal' => $product->price,
                'discount_total' => 0,
                'tax_total' => 0,
                'total' => $product->price,
                'client_id' => $client->id,
            ]
        );

        QuotationItem::firstOrCreate(
            [
                'quotation_id' => $quotation->id,
                'product_id' => $product->id,
            ],
            [
                'quantity' => 1,
                'unit_price' => $product->price,
                'discount_fixed' => 0,
                'discount_percent' => 0,
                'total_price' => $product->price,
            ]
        );
    }
}
