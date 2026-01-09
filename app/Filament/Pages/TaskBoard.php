<?php

namespace App\Filament\Pages;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;
use Modules\Tasks\Models\Task;
use Illuminate\Database\Eloquent\Builder;
use Relaticle\Flowforge\Board;
use Relaticle\Flowforge\BoardPage;
use Relaticle\Flowforge\Column;
use UnitEnum;

class TaskBoard extends BoardPage
{
    protected static string|null|\BackedEnum $navigationIcon = 'heroicon-o-view-columns';
    protected static ?string $navigationLabel = 'Task Board';
    protected static string | UnitEnum | null $navigationGroup = 'Tasks';
    protected static ?string $title = 'Task Board';

    public function board(Board $board): Board
    {
        return $board
            ->query($this->getEloquentQuery())
            ->recordTitleAttribute('title')
            ->columnIdentifier('status')
            ->positionIdentifier('position') // Enable drag-and-drop with position field
            ->columns([
                Column::make('New')->label('New Tasks')->color('gray'),
                Column::make('In Progress')->label('In Progress')->color('info'),
                Column::make('Review')->label('Under Review')->color('warning'),
                Column::make('Completed')->label('Completed')->color('success'),
            ])
            ->cardSchema(fn (Schema $schema) => $schema->components([
                TextEntry::make('priority')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'High' => 'danger',
                        'Medium' => 'warning',
                        'Low' => 'success',
                    }),
            ]));
    }

    public function getEloquentQuery(): Builder
    {
        return Task::query();
    }
}
