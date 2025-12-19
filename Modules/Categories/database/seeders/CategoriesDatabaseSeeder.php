<?php

namespace Modules\Categories\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Categories\Models\Category;

class CategoriesDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => ['en' => 'Social Media Marketing', 'ar' => 'تسويق عبر وسائل التواصل الاجتماعي'],
                'description' => 'Manage campaigns on Facebook, Instagram, TikTok, etc.',
                'priority' => 1,
                'image_path' => null,
            ],
            [
                'name' => ['en' => 'SEO', 'ar' => 'تحسين محركات البحث'],
                'description' => 'Optimize websites to rank higher in search engines',
                'priority' => 2,
                'image_path' => null,
            ],
            [
                'name' => ['en' => 'Content Marketing', 'ar' => 'تسويق المحتوى'],
                'description' => 'Create blogs, videos, and posts to engage your audience',
                'priority' => 3,
                'image_path' => null,
            ],
            [
                'name' => ['en' => 'Email Marketing', 'ar' => 'التسويق عبر البريد الإلكتروني'],
                'description' => 'Send campaigns and newsletters to your clients',
                'priority' => 4,
                'image_path' => null,
            ],
            [
                'name' => ['en' => 'PPC Advertising', 'ar' => 'الإعلانات المدفوعة بالنقرة'],
                'description' => 'Run Google Ads and paid campaigns',
                'priority' => 5,
                'image_path' => null,
            ],
        ];

        foreach ($categories as $data) {
            Category::firstOrCreate(
                ['name->en' => $data['name']['en']],
                $data
            );
        }
    }
}
