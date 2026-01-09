<?php

namespace App\Filament\Resources\Tasks\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class TaskInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('title')
                    ->label('Task Title'),

                TextEntry::make('project.name')
                    ->label('Project'),

                TextEntry::make('employee.name')
                    ->label('Assigned To'),

                TextEntry::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'New' => 'gray',
                        'In Progress' => 'warning',
                        'Review' => 'info',
                        'Completed' => 'success',
                    }),

                TextEntry::make('priority')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'High' => 'danger',
                        'Medium' => 'warning',
                        'Low' => 'success',
                        default => 'gray',
                    }),

                TextEntry::make('due_date')
                    ->label('Due Date')
                    ->date(),

                TextEntry::make('description')
                    ->label('Description'),

                TextEntry::make('created_at')
                    ->label('Created At')
                    ->dateTime('Y-m-d'),
            ]);
    }
}
