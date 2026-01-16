<?php

namespace App\Filament\Resources\Attendances\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Schema;

class AttendanceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                DatePicker::make('date')
                    ->disabled()
                    ->label(__('attendances.fields.date')),

                Select::make('employee_id')
                    ->relationship('employee', 'name')
                    ->disabled()
                    ->label(__('attendances.fields.employee')),

                TimePicker::make('check_in')
                    ->label(__('attendances.fields.check_in'))
                    ->seconds(false),

                TimePicker::make('check_out')
                    ->label(__('attendances.fields.check_out'))
                    ->seconds(false)
                    ->after('check_in'),

                TextInput::make('break_duration')
                    ->label(__('attendances.fields.break_duration_minutes'))
                    ->numeric()
                    ->disabled(),

                TextInput::make('total_working_hours')
                    ->label(__('attendances.fields.total_working_hours'))
                    ->disabled(),

                TextInput::make('overtime_hours')
                    ->label(__('attendances.fields.overtime_hours'))
                    ->disabled(),
            ]);
    }
}
