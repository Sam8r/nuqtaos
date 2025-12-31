<?php

namespace App\Filament\Resources\LeaveRequests\Schemas;

use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Modules\Settings\Models\Setting;

class LeaveRequestForm
{
    public static function configure(Schema $schema): Schema
    {
        $maxEncashmentDays = (int) Setting::value('encashment_limit');

        return $schema
            ->components([
                Repeater::make('selected_dates')
                    ->label('Selected Dates')
                    ->schema([
                        DatePicker::make('date')
                            ->required()
                            ->distinct()
                            ->minDate(now()),
                    ])
                    ->minItems(1)
                    ->columns(1)
                    ->reactive()
                    ->afterStateUpdated(function (Get $get, Set $set) use ($maxEncashmentDays) {
                        $count = count($get('selected_dates') ?? []);

                        // If dates exceed settings limit, disable encashment
                        if ($count > $maxEncashmentDays) {
                            $set('is_encashed', false);
                        }
                    }),

                Select::make('type')
                    ->label('Leave Type')
                    ->options([
                        'Paid' => 'Paid',
                        'Unpaid' => 'Unpaid',
                    ])
                    ->reactive()
                    ->required()
                    ->afterStateUpdated(fn (Set $set) => $set('is_encashed', false)),

                Checkbox::make('is_encashed')
                    ->label('Request Cash Encashment')
                    ->hidden(fn (Get $get) => $get('type') !== 'paid')
                    ->disabled(fn (Get $get) => count($get('selected_dates') ?? []) > $maxEncashmentDays)
                    ->reactive()
                    ->rules([
                        fn (Get $get): \Closure => function (string $attribute, $value, \Closure $fail) use ($get, $maxEncashmentDays) {
                            if ($value === true && count($get('selected_dates') ?? []) > $maxEncashmentDays) {
                                $fail('You have exceeded the maximum allowed encashment limit.');
                            }
                        },
                    ]),

                TextInput::make('reason')
                    ->label('Reason')
                    ->columnSpanFull(),
            ]);
    }
}
