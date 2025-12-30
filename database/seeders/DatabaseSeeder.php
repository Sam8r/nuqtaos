<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Modules\Categories\Database\Seeders\CategoriesDatabaseSeeder;
use Modules\Clients\Database\Seeders\ClientsDatabaseSeeder;
use Modules\Departments\Database\Seeders\DepartmentsDatabaseSeeder;
use Modules\Employees\Database\Seeders\EmployeesDatabaseSeeder;
use Modules\Positions\Database\Seeders\PositionsDatabaseSeeder;
use Modules\Products\Database\Seeders\ProductsDatabaseSeeder;
use Modules\Quotations\Database\Seeders\QuotationsDatabaseSeeder;
use Modules\Settings\Database\Seeders\SettingsDatabaseSeeder;

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
            SettingsDatabaseSeeder::class,
            CategoriesDatabaseSeeder::class,
            ClientsDatabaseSeeder::class,
            ProductsDatabaseSeeder::class,
            QuotationsDatabaseSeeder::class,
            DepartmentsDatabaseSeeder::class,
            PositionsDatabaseSeeder::class,
            EmployeesDatabaseSeeder::class,
        ]);
    }
}
