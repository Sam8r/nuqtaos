<?php

namespace App\Filament\Resources\FinancialAdjustments\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class FinancialAdjustmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('employee_id')
                    ->relationship('employee', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),

                Select::make('type')
                    ->required()
                    ->reactive()
                    ->options([
                        'Bonus' => 'Bonus',
                        'Deduction' => 'Deduction',
                    ]),

                Select::make('category')
                    ->required()
                    ->options(fn ($get) =>
                    $get('type') === 'bonus'
                        ? [
                        'Performance-based' => 'Performance-based',
                        'Attendance-based'  => 'Attendance-based',
                        'Manual Bonus'      => 'Manual Bonus',
                    ]
                        : [
                        'Leave Overuse'          => 'Leave Overuse',
                        'Absence Without Notice' => 'Absence Without Notice',
                        'Penalty'                => 'Penalty',
                        'Loan / Advance'         => 'Loan / Advance',
                    ]
                    ),

                TextInput::make('amount')
                    ->numeric()
                    ->minValue(0)
                    ->helperText('Leave empty if calculated by days'),

                TextInput::make('days_count')
                    ->numeric()
                    ->minValue(1)
                    ->visible(fn ($get) => $get('type') === 'Deduction'),

                Textarea::make('reason')
                    ->required(),

                DatePicker::make('processing_date')
                    ->required(),
            ]);
    }
}
