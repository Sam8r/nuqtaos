<?php

namespace App\Filament\Resources\Payrolls\Schemas;

use Carbon\Carbon;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Modules\Attendances\Models\Attendance;
use Modules\Employees\Models\Employee;
use Modules\Settings\Models\Setting;

class PayrollForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Tabs::make('payroll_process')
                    ->label(__('payrolls.tabs.process'))
                    ->tabs([
                        Tabs\Tab::make(__('payrolls.tabs.salary_overview'))
                            ->icon('heroicon-o-banknotes')
                            ->schema([
                                Section::make(__('payrolls.sections.selection_period'))
                                    ->schema([
                                        Grid::make(2)->schema([
                                            Select::make('employee_id')
                                                ->label(__('payrolls.fields.employee'))
                                                ->relationship('employee', 'name')
                                                ->searchable()
                                                ->preload()
                                                ->required()
                                                ->reactive()
                                                ->afterStateUpdated(fn(Get $get, Set $set) => self::calculatePayroll($get, $set)),
                                            TextInput::make('month_year')
                                                ->label(__('payrolls.fields.month_year'))
                                                ->type('month')
                                                ->required()
                                                ->reactive()
                                                ->afterStateUpdated(fn(Get $get, Set $set) => self::calculatePayroll($get, $set)),
                                        ]),
                                    ]),

                                Section::make(__('payrolls.sections.results'))
                                    ->schema([
                                        Grid::make(3)->schema(
                                            self::salarySummaryFields()
                                        ),
                                    ]),
                            ]),
                    ])->columnSpanFull(),
            ]);
    }

    protected static function salarySummaryFields(): array
    {
        $currency = Setting::value('salary_currency') ?? 'USD';

        return [
            TextInput::make('basic_salary')
                ->label(__('payrolls.fields.basic_salary'))
                ->disabled()
                ->dehydrated()
                ->prefix($currency),

            TextInput::make('daily_rate')
                ->label(__('payrolls.fields.daily_rate'))
                ->disabled()
                ->dehydrated()
                ->prefix($currency),

            TextInput::make('working_days_target')
                ->label(__('payrolls.fields.working_days_target'))
                ->disabled()
                ->dehydrated(),

            TextInput::make('bonuses_total')
                ->label(__('payrolls.fields.bonuses_total'))
                ->disabled()
                ->dehydrated()
                ->prefix($currency),

            TextInput::make('deductions_total')
                ->label(__('payrolls.fields.deductions_total'))
                ->disabled()
                ->dehydrated()
                ->prefix($currency),

            TextInput::make('net_salary')
                ->label(__('payrolls.fields.net_salary'))
                ->disabled()
                ->dehydrated()
                ->prefix($currency)
                ->columnSpanFull()
                ->extraInputAttributes([
                    'style' => 'font-weight: bold; color: #10b981; font-size: 1.25rem;',
                ]),
        ];
    }

    private static function calculatePayroll(Get $get, Set $set): void
    {
        $employeeId = $get('employee_id');
        $monthYear  = $get('month_year');

        if (!$employeeId || !$monthYear) {
            return;
        }

        $employee = Employee::find($employeeId);
        $settings = Setting::first();

        /*
        |--------------------------------------------------------------------------
        | 1. Payroll Cycle Dates
        |--------------------------------------------------------------------------
        */
        $startDay = $employee->payroll_start_day
            ?? $settings->default_payroll_start_day
            ?? 1;

        $cycleStartDate = Carbon::parse($monthYear)->day($startDay);
        $cycleEndDate   = $cycleStartDate->copy()->addMonth()->subDay();

        /*
        |--------------------------------------------------------------------------
        | 2. Hiring Date Override
        |--------------------------------------------------------------------------
        */
        $joiningDate     = Carbon::parse($employee->joining_date);
        $actualStartDate = $joiningDate->gt($cycleStartDate)
            ? $joiningDate
            : $cycleStartDate;

        /*
        |--------------------------------------------------------------------------
        | 3. Working Days Target (Exclude Weekends)
        |--------------------------------------------------------------------------
        */
        $weekends = $settings->weekends ?? [];
        $workingDaysRequired = 0;

        $tempDate = $actualStartDate->copy();
        while ($tempDate->lte($cycleEndDate)) {
            if (!in_array($tempDate->format('l'), $weekends)) {
                $workingDaysRequired++;
            }
            $tempDate->addDay();
        }

        /*
        |--------------------------------------------------------------------------
        | 4. Salary & Daily Rate
        |--------------------------------------------------------------------------
        */
        $salary      = $employee->salary;
        $dailySalary = $salary / 30;

        /*
        |--------------------------------------------------------------------------
        | 5. Work Hours (Employee Override → Settings)
        |--------------------------------------------------------------------------
        */
        $workFrom = $employee->work_start
            ? Carbon::parse($employee->work_start)
            : Carbon::parse($settings->default_work_from);

        $workTo = $employee->work_end
            ? Carbon::parse($employee->work_end)
            : Carbon::parse($settings->default_work_to);

        $dailyWorkingHours = max(
            0,
            $workFrom->diffInMinutes($workTo) / 60
        );

        $hourlyRate = $dailyWorkingHours > 0
            ? $dailySalary / $dailyWorkingHours
            : 0;

        /*
        |--------------------------------------------------------------------------
        | 6. Attendance & Absence
        |--------------------------------------------------------------------------
        */
        $attendances = Attendance::where('employee_id', $employeeId)
            ->whereBetween('date', [
                $actualStartDate->toDateString(),
                $cycleEndDate->toDateString(),
            ])
            ->get();

        $actualAttendanceDays = $attendances->count();
        $totalOvertimeHours  = $attendances->sum('overtime_hours');

        $absenceDays = max(0, $workingDaysRequired - $actualAttendanceDays);
        $absenceDeduction = $absenceDays * $dailySalary;

        /*
        |--------------------------------------------------------------------------
        | 7. Overtime Calculation
        |--------------------------------------------------------------------------
        */
        if ($settings->overtime_active_mode === 'fixed') {
            $overtimePay = $totalOvertimeHours * $settings->overtime_fixed_rate;
        } else {
            $multiplier  = $settings->overtime_percentage ?? 1.5;
            $overtimePay = $totalOvertimeHours * $hourlyRate * $multiplier;
        }

        /*
        |--------------------------------------------------------------------------
        | 8. Totals
        |--------------------------------------------------------------------------
        */
        $totalBonuses    = $overtimePay;
        $totalDeductions = $absenceDeduction;

        $netSalary = $salary + $totalBonuses - $totalDeductions;

        /*
        |--------------------------------------------------------------------------
        | 9. Set Form Values
        |--------------------------------------------------------------------------
        */
        $set('basic_salary', round($salary, 2));
        $set('daily_rate', round($dailySalary, 2));
        $set('working_days_target', $workingDaysRequired);
        $set('bonuses_total', round($totalBonuses, 2));
        $set('deductions_total', round($totalDeductions, 2));
        $set('net_salary', round($netSalary, 2));
    }
}
