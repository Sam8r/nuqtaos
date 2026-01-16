<?php

namespace App\Filament\Resources\Tasks\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class TaskForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label(__('tasks.fields.title'))
                    ->required(),

                Select::make('project_id')
                    ->label(__('tasks.fields.project'))
                    ->relationship('project', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),

                Select::make('employee_id')
                    ->label(__('tasks.fields.assigned_to'))
                    ->relationship('employee', 'name')
                    ->preload()
                    ->searchable(),

                Select::make('priority')
                    ->label(__('tasks.fields.priority'))
                    ->options([
                        'Low' => __('tasks.priorities.Low'),
                        'Medium' => __('tasks.priorities.Medium'),
                        'High' => __('tasks.priorities.High'),
                    ])->default('Medium'),

                Select::make('status')
                    ->label(__('tasks.fields.status'))
                    ->options([
                        'New' => __('tasks.statuses.New'),
                        'In Progress' => __('tasks.statuses.In Progress'),
                        'Review' => __('tasks.statuses.Review'),
                        'Completed' => __('tasks.statuses.Completed'),
                    ])->default('New'),

                DatePicker::make('due_date')
                    ->label(__('tasks.fields.due_date')),

                Textarea::make('description')
                    ->label(__('tasks.fields.description'))
                    ->rows(3),
            ]);
    }
}
