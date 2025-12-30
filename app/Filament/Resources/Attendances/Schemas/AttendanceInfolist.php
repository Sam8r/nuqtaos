<?php

namespace App\Filament\Resources\Attendances\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class AttendanceInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('date')
                    ->label('Date')
                    ->date(),

                TextEntry::make('employee.name')
                    ->label('Employee'),

                TextEntry::make('check_in')
                    ->label('Check In')
                    ->dateTime(),

                TextEntry::make('check_out')
                    ->label('Check Out')
                    ->dateTime()
                    ->placeholder('Not checked out'),

                TextEntry::make('break_duration')
                    ->label('Break Duration')
                    ->suffix(' minutes')
                    ->default('0'),

                TextEntry::make('total_working_hours')
                    ->label('Total Working Hours')
                    ->suffix(' hrs')
                    ->placeholder('Not calculated'),

                TextEntry::make('overtime_hours')
                    ->label('Overtime Hours')
                    ->suffix(' hrs'),
            ]);
    }
}
