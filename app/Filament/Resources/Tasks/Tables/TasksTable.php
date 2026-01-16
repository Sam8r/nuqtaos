<?php

namespace App\Filament\Resources\Tasks\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class TasksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label(__('tasks.fields.title'))
                    ->searchable(),
                TextColumn::make('project.name')
                    ->label(__('tasks.fields.project')),
                TextColumn::make('employee.name')
                    ->label(__('tasks.fields.assigned_to')),
                TextColumn::make('status')
                    ->badge()
                    ->label(__('tasks.fields.status'))
                    ->color(fn (string $state): string => match ($state) {
                        'New' => 'gray',
                        'In Progress' => 'warning',
                        'Review' => 'info',
                        'Completed' => 'success',
                    })
                    ->formatStateUsing(fn (string $state) => __('tasks.statuses.' . $state)),
                TextColumn::make('priority')
                    ->badge()
                    ->label(__('tasks.fields.priority'))
                    ->color(fn (string $state): string => match ($state) {
                        'High' => 'danger',
                        'Medium' => 'warning',
                        'Low' => 'success',
                    })
                    ->formatStateUsing(fn (string $state) => __('tasks.priorities.' . $state)),
                TextColumn::make('due_date')
                    ->label(__('tasks.fields.due_date'))
                    ->date()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label(__('tasks.filters.status'))
                    ->options([
                        'New' => __('tasks.statuses.New'),
                        'In Progress' => __('tasks.statuses.In Progress'),
                        'Review' => __('tasks.statuses.Review'),
                        'Completed' => __('tasks.statuses.Completed'),
                    ]),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
