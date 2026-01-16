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
                    ->label(__('tasks.fields.title')),

                TextEntry::make('project.name')
                    ->label(__('tasks.fields.project')),

                TextEntry::make('employee.name')
                    ->label(__('tasks.fields.assigned_to')),

                TextEntry::make('status')
                    ->badge()
                    ->label(__('tasks.fields.status'))
                    ->color(fn (string $state): string => match ($state) {
                        'New' => 'gray',
                        'In Progress' => 'warning',
                        'Review' => 'info',
                        'Completed' => 'success',
                    })
                    ->formatStateUsing(fn (string $state) => __('tasks.statuses.' . $state)),

                TextEntry::make('priority')
                    ->badge()
                    ->label(__('tasks.fields.priority'))
                    ->color(fn (string $state): string => match ($state) {
                        'High' => 'danger',
                        'Medium' => 'warning',
                        'Low' => 'success',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state) => __('tasks.priorities.' . $state)),

                TextEntry::make('due_date')
                    ->label(__('tasks.fields.due_date'))
                    ->date(),

                TextEntry::make('description')
                    ->label(__('tasks.fields.description')),

                TextEntry::make('created_at')
                    ->label(__('tasks.fields.created_at'))
                    ->dateTime('Y-m-d'),
            ]);
    }
}
