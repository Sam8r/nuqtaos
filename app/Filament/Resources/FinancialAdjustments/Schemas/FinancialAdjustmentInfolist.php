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
                    ->label('Employee'),

                TextEntry::make('type')
                    ->badge()
                    ->colors([
                        'success' => 'Bonus',
                        'danger' => 'Deduction',
                    ]),

                TextEntry::make('category')
                    ->label('Category'),

                TextEntry::make('amount')
                    ->label('Amount')
                    ->money($currency),

                TextEntry::make('days_count')
                    ->label('Days Count'),

                TextEntry::make('processing_date')
                    ->label('Processing Date')
                    ->date(),

                TextEntry::make('reason')
                    ->label('Reason')
                    ->columnSpanFull(),

                TextEntry::make('status')
                    ->badge()
                    ->colors([
                        'warning' => 'Pending',
                        'success' => 'Processed',
                        'danger' => 'Rejected',
                    ]),

                TextEntry::make('created_at')
                    ->label('Created At')
                    ->dateTime(),
            ]);
    }
}
