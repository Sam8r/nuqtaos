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
                    ->label(__('attendances.fields.date'))
                    ->date(),

                TextEntry::make('employee.name')
                    ->label(__('attendances.fields.employee_name')),

                TextEntry::make('check_in')
                    ->label(__('attendances.fields.check_in'))
                    ->dateTime(),

                TextEntry::make('check_out')
                    ->label(__('attendances.fields.check_out'))
                    ->dateTime()
                    ->placeholder(__('attendances.messages.not_checked_out')),

                TextEntry::make('break_duration')
                    ->label(__('attendances.fields.break_duration'))
                    ->suffix(__('attendances.suffixes.minutes'))
                    ->default('0'),

                TextEntry::make('total_working_hours')
                    ->label(__('attendances.fields.total_working_hours'))
                    ->suffix(__('attendances.suffixes.hours'))
                    ->placeholder(__('attendances.messages.not_calculated')),

                TextEntry::make('overtime_hours')
                    ->label(__('attendances.fields.overtime_hours'))
                    ->suffix(__('attendances.suffixes.hours')),
            ]);
    }
}
