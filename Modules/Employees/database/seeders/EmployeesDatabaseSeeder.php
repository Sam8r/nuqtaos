<?php

namespace Modules\Employees\Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Modules\Departments\Models\Department;
use Modules\Employees\Models\Employee;
use Modules\Positions\Models\Position;

class EmployeesDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first();
        $position = Position::first();
        $department = Department::first();

        Employee::firstOrCreate(
            [
                'user_id' => $user->id,
            ],
            [
                'name' => [
                    'en' => 'Ahmed Ali',
                    'ar' => 'أحمد علي',
                ],
                'phone' => '+201234567890',
                'emergency_contact' => '+201234567890',
                'joining_date' => now(),
                'contract_type' => 'Full Time',
                'status' => 'Active',
                'work_start' => '09:00',
                'work_end' => '17:00',
                'position_id' => $position->id,
                'department_id' => $department->id,
            ]
        );
    }
}
