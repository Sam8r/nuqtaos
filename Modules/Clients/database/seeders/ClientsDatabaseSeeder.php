<?php

namespace Modules\Clients\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Clients\Models\Client;

class ClientsDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Client::firstOrCreate(
            ['company_name' => ['en' => 'Test Client', 'ar' => 'عميل تجريبي']],
            [
                'address' => '123 Street',
                'status' => 'Active',
                'tier' => 'Gold',
                'country' => 'Egypt',
                'credit_limit' => 0,
            ]
        );
    }
}
