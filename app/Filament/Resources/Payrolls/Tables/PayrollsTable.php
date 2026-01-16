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
        $currency = Setting::value('salary_currency') ?? 'USD';

        return $table
            ->columns([
                TextColumn::make('employee.name')
                    ->label(__('payrolls.fields.employee'))
                    ->searchable(),

                TextColumn::make('month_year')
                    ->label(__('payrolls.fields.month_year'))
                    ->date('F Y')
                    ->sortable(),

                TextColumn::make('basic_salary')
                    ->label(__('payrolls.fields.basic_salary'))
                    ->money($currency),

                TextColumn::make('bonuses_total')
                    ->label(__('payrolls.fields.bonuses_total'))
                    ->money($currency)
                    ->color('success'),

                TextColumn::make('leaves_deduction')
                    ->label(__('payrolls.fields.leaves_deduction'))
                    ->money($currency)
                    ->color('danger'),

                TextColumn::make('deductions_total')
                    ->label(__('payrolls.fields.deductions_total'))
                    ->money($currency)
                    ->color('danger'),

                TextColumn::make('net_salary')
                    ->label(__('payrolls.fields.net_salary'))
                    ->money($currency)
                    ->weight('bold')
                    ->color('primary'),

                TextColumn::make('created_at')
                    ->label(__('payrolls.fields.created_at'))
                    ->dateTime(),
            ])
            ->defaultSort('month_year', 'desc')
            ->filters([
                SelectFilter::make('employee_id')
                    ->relationship('employee', 'name')
                    ->label(__('payrolls.filters.employee')),

                Filter::make('month_year')
                    ->form([
                        DatePicker::make('month')
                            ->label(__('payrolls.filters.month')),
                    ])
                    ->query(fn ($query, array $data) =>
                    $query->when($data['month'], fn($q) => $q->whereMonth('month_year', Carbon::parse($data['month'])->month))
                    )
            ])
            ->recordActions([
                ViewAction::make()
                    ->label(__('payrolls.actions.view')),
                EditAction::make()
                    ->label(__('payrolls.actions.edit')),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->label(__('payrolls.actions.delete')),
                ]),
            ]);
    }
}
