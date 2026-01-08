<?php

namespace App\Filament\Resources\Expenses\Schemas;

use App\Filament\Custom\TextEntry;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Modules\Settings\Models\Setting;

class ExpenseInfolist
{
    public static function configure(Schema $schema): Schema
    {
        $currency = Setting::value('currency');

        return $schema
            ->components([
                TextEntry::make('category.name')
                    ->label('Category'),

                TextEntry::make('amount')
                    ->money($currency),

                TextEntry::make('status')
                    ->colors([
                        'warning' => 'Pending',
                        'success' => 'Approved',
                        'danger'  => 'Rejected',
                    ])
                    ->badge(),

                TextEntry::make('expense_date')
                    ->date(),

                TextEntry::make('description'),

                TextEntry::make('submittedBy.name')
                    ->label('Submitted By'),

                TextEntry::make('approvedBy.name')
                    ->label('Approved By'),
            ]);
    }
}
