<?php

namespace App\Filament\Resources\FinancialAdjustments\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Modules\Settings\Models\Setting;

class FinancialAdjustmentsTable
{
    public static function configure(Table $table): Table
    {
        $currency = Setting::value('salary_currency');

        return $table
            ->columns([
                TextColumn::make('employee.name')
                    ->label('Employee')
                    ->searchable(),

                BadgeColumn::make('type')
                    ->colors([
                        'success' => 'Bonus',
                        'danger'  => 'Deduction',
                    ]),

                TextColumn::make('category')
                    ->badge(),

                TextColumn::make('amount')
                    ->money($currency),

                TextColumn::make('days_count')
                    ->label('Days'),

                TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'warning' => 'Pending',
                        'success' => 'Processed',
                        'danger'  => 'Rejected',
                    ]),

                TextColumn::make('processing_date')
                    ->date(),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->options([
                        'Bonus' => 'Bonus',
                        'Deduction' => 'Deduction',
                    ]),
                SelectFilter::make('status')
                    ->options([
                        'Pending' => 'Pending',
                        'Processed' => 'Processed',
                        'Rejected' => 'Rejected',
                    ]),
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
