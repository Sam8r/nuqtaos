<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Modules\Categories\Database\Seeders\CategoriesDatabaseSeeder;
use Modules\Clients\Database\Seeders\ClientsDatabaseSeeder;
use Modules\Products\Database\Seeders\ProductsDatabaseSeeder;
use Modules\Quotations\Database\Seeders\QuotationsDatabaseSeeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => bcrypt('password'),
            ]
        );

        $this->call([
            CategoriesDatabaseSeeder::class,
            ClientsDatabaseSeeder::class,
            ProductsDatabaseSeeder::class,
            QuotationsDatabaseSeeder::class,
        ]);
    }
}
