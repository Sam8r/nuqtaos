<?php

namespace App\Filament\Resources\FinancialAdjustments\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Lang;

class FinancialAdjustmentForm
{
    public static function configure(Schema $schema): Schema
    {
        $types = Lang::get('financial_adjustments.types');
        $categoryGroups = Lang::get('financial_adjustments.category_groups');

        return $schema
            ->components([
                Select::make('employee_id')
                    ->label(__('financial_adjustments.fields.employee'))
                    ->relationship('employee', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),

                Select::make('type')
                    ->label(__('financial_adjustments.fields.type'))
                    ->required()
                    ->live()
                    ->options($types),

                Select::make('category')
                    ->label(__('financial_adjustments.fields.category'))
                    ->required()
                    ->options(function ($get) use ($categoryGroups) {
                        return $categoryGroups[$get('type')] ?? [];
                    }),

                TextInput::make('amount')
                    ->label(__('financial_adjustments.fields.amount_cash'))
                    ->numeric()
                    ->minValue(0)
                    ->live(debounce: 300)
                    ->required(fn (Get $get) => empty($get('days_count')))
                    ->disabled(fn (Get $get) => !empty($get('days_count'))),

                TextInput::make('days_count')
                    ->label(__('financial_adjustments.fields.days_count'))
                    ->numeric()
                    ->minValue(1)
                    ->live(debounce: 300)
                    ->visible(fn (Get $get) => $get('type') === 'Deduction')
                    ->required(fn (Get $get) => $get('type') === 'Deduction' && empty($get('amount')))
                    ->disabled(fn (Get $get) => !empty($get('amount'))),

                Textarea::make('reason')
                    ->label(__('financial_adjustments.fields.reason'))
                    ->required(),

                DatePicker::make('processing_date')
                    ->label(__('financial_adjustments.fields.processing_date'))
                    ->required(),

                Hidden::make('status')
                    ->default('Pending'),
            ]);
    }
}
