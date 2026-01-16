<?php

namespace App\Filament\Resources\FinancialAdjustments\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;
use Modules\Settings\Models\Setting;

class FinancialAdjustmentInfolist
{
    public static function configure(Schema $schema): Schema
    {
        $currency = Setting::value('salary_currency');

        return $schema
            ->components([
                TextEntry::make('employee.name')
                    ->label(__('financial_adjustments.fields.employee')),

                TextEntry::make('type')
                    ->badge()
                    ->colors([
                        'success' => 'Bonus',
                        'danger' => 'Deduction',
                    ])
                    ->formatStateUsing(fn ($state) => __('financial_adjustments.types.' . $state)),

                TextEntry::make('category')
                    ->label(__('financial_adjustments.fields.category'))
                    ->formatStateUsing(fn ($state) => __('financial_adjustments.categories.' . $state)),

                TextEntry::make('amount')
                    ->label(__('financial_adjustments.fields.amount'))
                    ->money($currency),

                TextEntry::make('days_count')
                    ->label(__('financial_adjustments.fields.days_count')),

                TextEntry::make('processing_date')
                    ->label(__('financial_adjustments.fields.processing_date'))
                    ->date(),

                TextEntry::make('reason')
                    ->label(__('financial_adjustments.fields.reason'))
                    ->columnSpanFull(),

                TextEntry::make('status')
                    ->badge()
                    ->colors([
                        'warning' => 'Pending',
                        'success' => 'Processed',
                        'danger' => 'Rejected',
                    ])
                    ->formatStateUsing(fn ($state) => __('financial_adjustments.statuses.' . $state)),

                TextEntry::make('created_at')
                    ->label(__('financial_adjustments.fields.created_at'))
                    ->dateTime(),
            ]);
    }
}
