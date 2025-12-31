<?php

namespace Modules\Leaves\Console;

use Illuminate\Console\Command;
use Modules\Employees\Models\Employee;
use Modules\Leaves\Models\LeaveBalance;
use Modules\Settings\Models\Setting;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class MonthlyLeaveRollover extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'leaves:monthly-rollover';

    /**
     * The console command description.
     */
    protected $description = 'Rollover unused leave balances to the next month and add new days.';

    public function handle()
    {
        $lastMonthDate = now()->subMonth();
        $currentMonth = now()->month;
        $currentYear = now()->year;

        $monthlyLimit = (int) Setting::value('monthly_leave_limit');
        $maxAccumulation = (int) Setting::value('max_accumulation');

        $employees = Employee::all();

        $this->info("Starting leave rollover for " . $employees->count() . " employees...");

        foreach ($employees as $employee) {
            $oldBalance = LeaveBalance::where('employee_id', $employee->id)
                ->where('month', $lastMonthDate->month)
                ->where('year', $lastMonthDate->year)
                ->first();

            // Calculate total unused days (Current + Accumulated from previous month)
            $totalUnused = $oldBalance ? ($oldBalance->current_balance + $oldBalance->accumulated_balance) : 0;

            // Calculate total unused days (Current + Accumulated from previous month) and cap it to max accumulation
            $carriedOver = min($totalUnused, $maxAccumulation);

            LeaveBalance::updateOrCreate(
                [
                    'employee_id' => $employee->id,
                    'month' => $currentMonth,
                    'year' => $currentYear,
                ],
                [
                    'current_balance' => $monthlyLimit,
                    'accumulated_balance' => $carriedOver,
                ]
            );
        }

        $this->info('Leave rollover completed successfully for ' . now()->format('F Y'));
    }
}
