<?php

namespace App\Filament\Resources\Expenses\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Modules\Settings\Models\Setting;

class ExpensesTable
{
    public static function configure(Table $table): Table
    {
        $currency = Setting::value('currency');

        return $table
            ->columns([
                TextColumn::make('category.name')
                    ->label(__('expenses.fields.category')),

                TextColumn::make('amount')
                    ->label(__('expenses.fields.amount'))
                    ->money($currency),

                TextColumn::make('status')
                    ->label(__('expenses.fields.status'))
                    ->colors([
                        'warning' => 'Pending',
                        'success' => 'Approved',
                        'danger'  => 'Rejected',
                    ])
                    ->badge()
                    ->formatStateUsing(fn (string $state) => __('expenses.statuses.' . $state)),

                TextColumn::make('expense_date')
                    ->label(__('expenses.fields.expense_date'))
                    ->date(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label(__('expenses.filters.status'))
                    ->options([
                        'Pending' => __('expenses.statuses.Pending'),
                        'Approved' => __('expenses.statuses.Approved'),
                        'Rejected' => __('expenses.statuses.Rejected'),
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
