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
        $currency = Setting::value('salary_currency') ?? 'USD';

        return $table
            ->columns([
                TextColumn::make('employee.name')
                    ->label(__('financial_adjustments.fields.employee'))
                    ->searchable(),

                BadgeColumn::make('type')
                    ->label(__('financial_adjustments.fields.type'))
                    ->colors([
                        'success' => 'Bonus',
                        'danger'  => 'Deduction',
                    ])
                    ->formatStateUsing(fn ($state) => __('financial_adjustments.types.' . $state)),

                TextColumn::make('category')
                    ->label(__('financial_adjustments.fields.category'))
                    ->badge()
                    ->formatStateUsing(fn ($state) => __('financial_adjustments.categories.' . $state)),

                TextColumn::make('amount')
                    ->label(__('financial_adjustments.fields.amount'))
                    ->money($currency),

                TextColumn::make('days_count')
                    ->label(__('financial_adjustments.fields.days_count')),

                TextColumn::make('status')
                    ->label(__('financial_adjustments.fields.status'))
                    ->badge()
                    ->colors([
                        'warning' => 'Pending',
                        'success' => 'Processed',
                        'danger'  => 'Rejected',
                    ])
                    ->formatStateUsing(fn ($state) => __('financial_adjustments.statuses.' . $state)),

                TextColumn::make('processing_date')
                    ->label(__('financial_adjustments.fields.processing_date'))
                    ->date(),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->label(__('financial_adjustments.filters.type'))
                    ->options([
                        'Bonus' => __('financial_adjustments.types.Bonus'),
                        'Deduction' => __('financial_adjustments.types.Deduction'),
                    ]),
                SelectFilter::make('status')
                    ->label(__('financial_adjustments.filters.status'))
                    ->options([
                        'Pending' => __('financial_adjustments.statuses.Pending'),
                        'Processed' => __('financial_adjustments.statuses.Processed'),
                        'Rejected' => __('financial_adjustments.statuses.Rejected'),
                    ]),
            ])
            ->recordActions([
                ViewAction::make()
                    ->label(__('financial_adjustments.actions.view')),
                EditAction::make()
                    ->label(__('financial_adjustments.actions.edit')),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->label(__('financial_adjustments.actions.delete')),
                ]),
            ]);
    }
}
