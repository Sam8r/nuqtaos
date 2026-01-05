<?php

namespace App\Filament\Resources\FinancialAdjustments\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
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
                    ->live()
                    ->options([
                        'Bonus' => 'Bonus',
                        'Deduction' => 'Deduction',
                    ]),

                Select::make('category')
                    ->required()
                    ->options(fn ($get) =>
                    $get('type') === 'Bonus'
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
                    ->label('Amount (Cash)')
                    ->live(debounce: 300)
                    ->required(fn (Get $get) => empty($get('days_count')))
                    ->disabled(fn (Get $get) => !empty($get('days_count'))),

                TextInput::make('days_count')
                    ->numeric()
                    ->minValue(1)
                    ->label('Days Count')
                    ->live(debounce: 300)
                    ->visible(fn (Get $get) => $get('type') === 'Deduction')
                    ->required(fn (Get $get) => $get('type') === 'Deduction' && empty($get('amount')))
                    ->disabled(fn (Get $get) => !empty($get('amount'))),

                Textarea::make('reason')
                    ->required(),

                DatePicker::make('processing_date')
                    ->required(),

                Hidden::make('status')
                    ->default('Pending'),
            ]);
    }
}
