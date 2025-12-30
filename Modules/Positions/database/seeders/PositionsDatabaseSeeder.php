<?php

namespace Modules\Positions\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Departments\Models\Department;
use Modules\Positions\Models\Position;

class PositionsDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $department = Department::first();

        Position::firstOrCreate(
            [
                'name' => [
                    'en' => 'Manager',
                    'ar' => 'مدير',
                ],
                'department_id' => $department->id,
            ]
        );
    }
}
