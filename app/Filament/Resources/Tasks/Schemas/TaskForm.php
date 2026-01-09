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
                    ->required(),

                Select::make('project_id')
                    ->relationship('project', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),

                Select::make('employee_id')
                    ->label('Assign To')
                    ->relationship('employee', 'name')
                    ->preload()
                    ->searchable(),

                Select::make('priority')
                    ->options([
                        'Low' => 'Low',
                        'Medium' => 'Medium',
                        'High' => 'High',
                    ])->default('Medium'),

                Select::make('status')
                    ->options([
                        'New' => 'New',
                        'In Progress' => 'In Progress',
                        'Review' => 'Review',
                        'Completed' => 'Completed',
                    ])->default('New'),

                DatePicker::make('due_date'),

                Textarea::make('description')->rows(3),
            ]);
    }
}
