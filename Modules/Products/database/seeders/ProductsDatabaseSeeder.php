<?php

namespace Modules\Products\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Products\Models\Product;

class ProductsDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'code' => 'PRD-001',
                'sku' => 'SKU-001',
                'name' => ['en' => 'Website Design', 'ar' => 'تصميم مواقع'],
                'description' => ['en' => 'Professional website design service', 'ar' => 'خدمة تصميم مواقع احترافية'],
                'price' => 500,
                'type' => 'Service',
                'unit' => 'package',
                'status' => 'Active',
                'category_id' => 1,
            ],
            [
                'code' => 'PRD-002',
                'sku' => 'SKU-002',
                'name' => ['en' => 'SEO Audit', 'ar' => 'تدقيق SEO'],
                'description' => ['en' => 'Complete SEO audit for your website', 'ar' => 'تدقيق SEO كامل لموقعك'],
                'price' => 300,
                'type' => 'Service',
                'unit' => 'package',
                'status' => 'Active',
                'category_id' => 2,
            ],
        ];

        foreach ($products as $data) {
            Product::firstOrCreate(
                ['code' => $data['code']],
                $data
            );
        }
    }
}
