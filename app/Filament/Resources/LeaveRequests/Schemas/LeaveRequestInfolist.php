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
                    ->label(__('leave_requests.fields.employee'))
                    ->url(fn (string $state, $record) => route('filament.admin.resources.employees.view', ['record' => $record->employee])),

                TextEntry::make('type')
                    ->label(__('leave_requests.fields.type'))
                    ->badge()
                    ->formatStateUsing(fn ($state) => __('leave_requests.types.' . strtolower($state))),

                TextEntry::make('status')
                    ->badge()
                    ->color(fn (string $state) => match ($state) {
                        'Approved' => 'success',
                        'Rejected' => 'danger',
                        default => 'warning',
                    })
                    ->formatStateUsing(fn ($state) => __('leave_requests.statuses.' . ucfirst($state))),

                TextEntry::make('total_days')
                    ->label(__('leave_requests.fields.total_days')),

                IconEntry::make('is_encashed')
                    ->label(__('leave_requests.fields.encashed'))
                    ->boolean(),

                RepeatableEntry::make('selected_dates')
                    ->label(__('leave_requests.fields.selected_dates'))
                    ->schema([
                        TextEntry::make('date')
                            ->label(__('leave_requests.fields.date')),
                    ]),

                TextEntry::make('reason')
                    ->label(__('leave_requests.fields.reason')),

                TextEntry::make('rejection_reason')
                    ->label(__('leave_requests.fields.rejection_reason'))
                    ->color('danger')
                    ->visible(fn ($record) => strtolower($record->status) === 'rejected' && $record->rejection_reason),

                TextEntry::make('created_at')
                    ->label(__('leave_requests.fields.created_at'))
                    ->date(),
            ])
            ->columns(2);
    }
}
