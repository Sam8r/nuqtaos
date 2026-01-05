<?php

namespace App\Filament\Resources\Payrolls\Schemas;

use Carbon\Carbon;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Modules\Attendances\Models\Attendance;
use Modules\Employees\Models\Employee;
use Modules\FinancialAdjustments\Models\FinancialAdjustment;
use Modules\Leaves\Models\LeaveRequest;
use Modules\Settings\Models\Setting;

class PayrollForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Tabs::make('Payroll Process')
                    ->tabs([
                        Tabs\Tab::make('Salary Overview')
                            ->icon('heroicon-o-banknotes')
                            ->schema([
                                Section::make('Selection & Period')
                                    ->schema([
                                        Grid::make(2)->schema([
                                            Select::make('employee_id')
                                                ->label('Employee')
                                                ->options(Employee::all()->pluck('name', 'id'))
                                                ->required()
                                                ->reactive()
                                                ->afterStateUpdated(fn(Get $get, Set $set) => self::calculatePayroll($get, $set)),
                                            TextInput::make('month_year')
                                                ->label('Calculation Month')
                                                ->type('month')
                                                ->required()
                                                ->reactive()
                                                ->afterStateUpdated(fn(Get $get, Set $set) => self::calculatePayroll($get, $set)),
                                        ]),
                                    ]),

                                Section::make('Payroll Results')
                                    ->schema([
                                        Grid::make(3)->schema([
                                            TextInput::make('basic_salary')->label('Basic Salary')->disabled()->dehydrated()->prefix('$'),
                                            TextInput::make('daily_rate')->label('Daily Rate (Fixed 1/30)')->disabled()->dehydrated()->prefix('$'),
                                            TextInput::make('working_days_target')->label('Target Working Days')->disabled()->dehydrated(),
                                            TextInput::make('bonuses_total')->label('Total Additions')->disabled()->dehydrated()->prefix('$'),
                                            TextInput::make('deductions_total')->label('Total Deductions')->disabled()->dehydrated()->prefix('$'),
                                            TextInput::make('net_salary')
                                                ->label('Net Salary')
                                                ->disabled()
                                                ->dehydrated()
                                                ->columnSpanFull()
                                                ->extraInputAttributes(['style' => 'font-weight: bold; color: #10b981; font-size: 1.25rem;']),
                                        ]),
                                    ]),
                            ]),
                    ])->columnSpanFull(),
            ]);
    }

    private static function calculatePayroll(Get $get, Set $set): void
    {
        $employeeId = $get('employee_id');
        $monthYear = $get('month_year');

        if (!$employeeId || !$monthYear) return;

        $employee = Employee::find($employeeId);
        $settings = Setting::first();

        // 1. تحديد نطاق دورة الرواتب (مثلاً من 7 يناير لـ 6 فبراير)
        $startDay = $employee->payroll_start_day ?? $settings->default_payroll_start_day ?? 1;
        $cycleStartDate = Carbon::parse($monthYear)->day($startDay);
        $cycleEndDate = $cycleStartDate->copy()->addMonth()->subDay();

        // 2. معالجة تاريخ التعيين (Override)
        // لو تعين في نص الدورة، نبدأ الحساب من تاريخ التعيين لضمان عدم الخصم بأثر رجعي
        $hiringDate = Carbon::parse($employee->hiring_date);
        $actualStartDate = $hiringDate->gt($cycleStartDate) ? $hiringDate : $cycleStartDate;

        // 3. حساب أيام العمل المطلوبة (Target) باستبعاد الويكند
        $weekends = $settings->weekends ?? ['Friday', 'Saturday'];
        $workingDaysRequired = 0;
        $tempDate = $actualStartDate->copy();

        while ($tempDate->lte($cycleEndDate)) {
            if (!in_array($tempDate->format('l'), $weekends)) {
                $workingDaysRequired++;
            }
            $tempDate->addDay();
        }

        // 4. الحسابات المالية الأساسية (نظام الـ 30 يوم)
        $salary = $employee->salary;
        $dailySalary = $salary / 30; // العرف الثابت

        // 5. تحليل الحضور الفعلي من البصمة
        $attendances = Attendance::where('employee_id', $employeeId)
            ->whereBetween('date', [$actualStartDate->toDateString(), $cycleEndDate->toDateString()])
            ->get();

        $actualAttendanceDays = $attendances->count();
        $totalOvertimeHours = $attendances->sum('overtime_hours');

        // 6. حساب الغياب (أي يوم عمل بدون بصمة = خصم)
        $absenceDays = max(0, $workingDaysRequired - $actualAttendanceDays);
        $totalAbsenceDeduction = $absenceDays * $dailySalary;

        // 7. حساب الأوفرتايم (بناءً على الساعات الفعلية)
        $overtimeMultiplier = $settings->overtime_percentage ?? 1.5;
        $hourlyRate = $dailySalary / 8; // بافتراض 8 ساعات عمل
        $overtimePay = $totalOvertimeHours * $hourlyRate * $overtimeMultiplier;

        // 8. تجميع التسويات والمكافآت (Adjustments)
        $actions = $get('adjustments_actions') ?? [];
        $adjBonuses = 0;
        $adjDeductions = 0;
        // ... (منطق التسويات يظل كما هو في كودك)

        // 9. النتائج النهائية
        $totalBonuses = $overtimePay + $adjBonuses;
        $totalDeductions = $totalAbsenceDeduction + $adjDeductions;
        $netSalary = $salary + $totalBonuses - $totalDeductions;

        // 10. تحديث الواجهة
        $set('basic_salary', round($salary, 2));
        $set('daily_rate', round($dailySalary, 2));
        $set('working_days_target', $workingDaysRequired);
        $set('bonuses_total', round($totalBonuses, 2));
        $set('deductions_total', round($totalDeductions, 2));
        $set('net_salary', round($netSalary, 2));
    }
}
