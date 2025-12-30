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
                    ->label('Date'),

                Select::make('employee_id')
                    ->relationship('employee', 'name')
                    ->disabled()
                    ->label('Employee'),

                TimePicker::make('check_in')
                    ->label('Check In')
                    ->seconds(false),

                TimePicker::make('check_out')
                    ->label('Check Out')
                    ->seconds(false)
                    ->after('check_in'),

                TextInput::make('break_duration')
                    ->label('Break Duration (minutes)')
                    ->numeric()
                    ->disabled(),

                TextInput::make('total_working_hours')
                    ->label('Total Working Hours')
                    ->disabled(),

                TextInput::make('overtime_hours')
                    ->label('Overtime Hours')
                    ->disabled(),
            ]);
    }
}
