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
                TextColumn::make('name')->label('Name')->searchable(),
                TextColumn::make('description')->label('Description')->limit(50),
                ImageColumn::make('image_path')->label('Image')->disk('public')->size(50)->action(
                    MediaAction::make('view')
                        ->media(fn ($state) => $state)
                        ->modalHeading('View Category Image')
                        ->slideOver()
                )
            ])
            ->filters([
                SelectFilter::make('priority')
                    ->label('Priority')
                    ->options([
                        1 => 'Important',
                        2 => 'Main',
                        3 => 'Secondary',
                    ]),
                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
