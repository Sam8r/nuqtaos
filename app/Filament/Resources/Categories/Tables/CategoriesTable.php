<?php

namespace App\Filament\Resources\Categories\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Hugomyb\FilamentMediaAction\Actions\MediaAction;

class CategoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('categories.fields.name'))
                    ->searchable(),
                TextColumn::make('description')
                    ->label(__('categories.fields.description'))
                    ->limit(50),
                ImageColumn::make('image_path')->label(__('categories.fields.image'))->disk('public')->size(50)->action(
                    MediaAction::make('view')
                        ->media(fn ($state) => $state)
                        ->modalHeading(__('categories.modals.view_image'))
                        ->slideOver()
                )
            ])
            ->filters([
                SelectFilter::make('priority')
                    ->label(__('categories.filters.priority'))
                    ->options([
                        1 => __('categories.priorities.1'),
                        2 => __('categories.priorities.2'),
                        3 => __('categories.priorities.3'),
                    ]),
                TrashedFilter::make()
                    ->label(__('categories.filters.trashed')),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->label(__('categories.actions.delete')),
                    ForceDeleteBulkAction::make()
                        ->label(__('categories.actions.force_delete')),
                    RestoreBulkAction::make()
                        ->label(__('categories.actions.restore')),
                ]),
            ]);
    }
}
