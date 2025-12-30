<?php

namespace Modules\Departments\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Departments\Models\Department;

class DepartmentsDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Department::firstOrCreate([
            'name' => [
                'en' => 'Human Resources',
                'ar' => 'الموارد البشرية',
            ]
        ]);
    }
}
