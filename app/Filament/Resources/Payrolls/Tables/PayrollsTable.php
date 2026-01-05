<?php

namespace App\Filament\Resources\Payrolls\Tables;

use Carbon\Carbon;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Modules\Settings\Models\Setting;

class PayrollsTable
{
    public static function configure(Table $table): Table
    {
        $currency = Setting::value('salary_currency');

        return $table
            ->columns([
                TextColumn::make('employee.name')
                    ->label('Employee')
                    ->searchable(),

                TextColumn::make('month_year')
                    ->label('Payroll Month')
                    ->date('F Y')
                    ->sortable(),

                TextColumn::make('basic_salary')
                    ->label('Basic')
                    ->money($currency),

                TextColumn::make('bonuses_total')
                    ->label('Bonuses')
                    ->money($currency)
                    ->color('success'),

                TextColumn::make('leaves_deduction')
                    ->label('Leave Deds')
                    ->money($currency)
                    ->color('danger'),

                TextColumn::make('deductions_total')
                    ->label('Total Deds')
                    ->money($currency)
                    ->color('danger'),

                TextColumn::make('net_salary')
                    ->label('Net Salary')
                    ->money($currency)
                    ->weight('bold')
                    ->color('primary'),

                TextColumn::make('created_at')
                    ->label('Generated On')
                    ->dateTime(),
            ])
            ->defaultSort('month_year', 'desc')
            ->filters([
                SelectFilter::make('employee_id')
                    ->relationship('employee', 'name')
                    ->label('Filter by Employee'),

                Filter::make('month_year')
                    ->form([
                        DatePicker::make('month'),
                    ])
                    ->query(fn ($query, array $data) =>
                    $query->when($data['month'], fn($q) => $q->whereMonth('month_year', Carbon::parse($data['month'])->month))
                    )
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
