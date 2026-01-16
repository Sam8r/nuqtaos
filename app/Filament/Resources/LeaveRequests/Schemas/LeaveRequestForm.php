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
use Illuminate\Support\Facades\Lang;
use Modules\Settings\Models\Setting;

class LeaveRequestForm
{
    public static function configure(Schema $schema): Schema
    {
        $maxEncashmentDays = (int) Setting::value('encashment_limit');
        $types = Lang::get('leave_requests.types');

        return $schema
            ->components([
                Repeater::make('selected_dates')
                    ->label(__('leave_requests.fields.selected_dates'))
                    ->schema([
                        DatePicker::make('date')
                            ->label(__('leave_requests.fields.date'))
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
                    ->label(__('leave_requests.fields.type'))
                    ->options($types)
                    ->reactive()
                    ->required()
                    ->afterStateUpdated(fn (Set $set) => $set('is_encashed', false)),

                Checkbox::make('is_encashed')
                    ->label(__('leave_requests.fields.is_encashed'))
                    ->hidden(fn (Get $get) => strtolower($get('type') ?? '') !== 'paid')
                    ->disabled(fn (Get $get) => count($get('selected_dates') ?? []) > $maxEncashmentDays)
                    ->reactive()
                    ->rules([
                        fn (Get $get): \Closure => function (string $attribute, $value, \Closure $fail) use ($get, $maxEncashmentDays) {
                            if ($value === true && count($get('selected_dates') ?? []) > $maxEncashmentDays) {
                                $fail(__('leave_requests.validation.encashment_limit_exceeded'));
                            }
                        },
                    ]),

                TextInput::make('reason')
                    ->label(__('leave_requests.fields.reason'))
                    ->columnSpanFull(),
            ]);
    }
}
