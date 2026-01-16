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
    protected static ?string $navigationLabel = null;
    protected static string | UnitEnum | null $navigationGroup = null;
    protected static ?string $title = null;

    public static function getNavigationLabel(): string
    {
        return __('tasks.board.navigation_label');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('tasks.board.navigation_group');
    }

    public function getTitle(): string
    {
        return __('tasks.board.title');
    }

    public function board(Board $board): Board
    {
        return $board
            ->query($this->getEloquentQuery())
            ->recordTitleAttribute('title')
            ->columnIdentifier('status')
            ->positionIdentifier('position') // Enable drag-and-drop with position field
            ->columns([
                Column::make('New')->label(__('tasks.board.columns.New'))->color('gray'),
                Column::make('In Progress')->label(__('tasks.board.columns.In Progress'))->color('info'),
                Column::make('Review')->label(__('tasks.board.columns.Review'))->color('warning'),
                Column::make('Completed')->label(__('tasks.board.columns.Completed'))->color('success'),
            ])
            ->cardSchema(fn (Schema $schema) => $schema->components([
                TextEntry::make('priority')
                    ->label(__('tasks.fields.priority'))
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'High' => 'danger',
                        'Medium' => 'warning',
                        'Low' => 'success',
                    })
                    ->formatStateUsing(fn (string $state) => __('tasks.board.priorities.' . $state)),
            ]));
    }

    public function getEloquentQuery(): Builder
    {
        return Task::query();
    }
}
