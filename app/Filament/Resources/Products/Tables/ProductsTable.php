<?php

namespace App\Filament\Resources\Products\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Infolists\Components\TextEntry;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')
                    ->label('Code')
                    ->searchable(),
                TextColumn::make('name')
                    ->label('Name')
                    ->searchable(),
                TextColumn::make('price')
                    ->label('Price')
                    ->sortable()
                    ->money(fn ($record) => $record->currency),
                ImageColumn::make('first_image')
                    ->label('Image')
                    ->getStateUsing(function ($record) {
                        return isset($record->images[0]) ? asset('storage/' . $record->images[0]) : null;
                    })
                    ->size(50)
                    ->rounded(),
                BadgeColumn::make('type')
                    ->label('Type')
                    ->colors([
                        'primary' => 'Service',
                        'success' => 'Physical',
                    ]),
                TextColumn::make('sku')
                    ->label('SKU')
                    ->searchable(),
                BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'success' => 'Active',
                        'warning' => 'Inactive',
                        'danger' => 'Discontinued',
                    ]),
                TextColumn::make('category.name')
                    ->label('Category')
                    ->searchable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'Active' => 'Active',
                        'Inactive' => 'Inactive',
                        'Discontinued' => 'Discontinued',
                    ]),
                SelectFilter::make('type')
                    ->label('Type')
                    ->options([
                        'Service' => 'Service',
                        'Physical' => 'Physical',
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
