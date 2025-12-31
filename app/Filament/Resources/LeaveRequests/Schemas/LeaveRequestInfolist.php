<?php

namespace App\Filament\Resources\LeaveRequests\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Schemas\Schema;

class LeaveRequestInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('employee.name')
                    ->label('Employee')
                    ->url(fn (string $state, $record) => route('filament.admin.resources.employees.view', ['record' => $record->employee])),

                TextEntry::make('type')
                    ->label('Leave Type')
                    ->badge(),

                TextEntry::make('status')
                    ->badge()
                    ->color(fn (string $state) => match ($state) {
                        'Approved' => 'success',
                        'Rejected' => 'danger',
                        default => 'warning',
                    }),

                TextEntry::make('total_days')
                    ->label('Total Days'),

                IconEntry::make('is_encashed')
                    ->label('Encashed')
                    ->boolean(),

                RepeatableEntry::make('selected_dates')
                    ->label('Selected Dates')
                    ->schema([
                        TextEntry::make('date')
                            ->label('Date'),
                    ]),

                TextEntry::make('reason'),

                TextEntry::make('rejection_reason')
                    ->label('Rejection Reason')
                    ->color('danger')
                    ->visible(fn ($record) => $record->status === 'rejected' && $record->rejection_reason),

                TextEntry::make('created_at')
                    ->label('Requested At')
                    ->date(),
            ])
            ->columns(2);
    }
}
