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
                    ->label(__('products.fields.code'))
                    ->searchable(),
                TextColumn::make('name')
                    ->label(__('products.fields.name'))
                    ->searchable(),
                TextColumn::make('price')
                    ->label(__('products.fields.price'))
                    ->sortable()
                    ->money(fn ($record) => $record->currency),
                ImageColumn::make('first_image')
                    ->label(__('products.labels.image'))
                    ->getStateUsing(function ($record) {
                        return isset($record->images[0]) ? asset('storage/' . $record->images[0]) : null;
                    })
                    ->size(50)
                    ->rounded(),
                BadgeColumn::make('type')
                    ->label(__('products.fields.type'))
                    ->colors([
                        'primary' => 'Service',
                        'success' => 'Physical',
                    ])
                    ->formatStateUsing(fn ($state) => __('products.types.' . $state)),
                TextColumn::make('sku')
                    ->label(__('products.fields.sku'))
                    ->searchable(),
                BadgeColumn::make('status')
                    ->label(__('products.fields.status'))
                    ->colors([
                        'success' => 'Active',
                        'warning' => 'Inactive',
                        'danger' => 'Discontinued',
                    ])
                    ->formatStateUsing(fn ($state) => __('products.statuses.' . $state)),
                TextColumn::make('category.name')
                    ->label(__('products.fields.category'))
                    ->searchable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label(__('products.filters.status'))
                    ->options([
                        'Active' => __('products.statuses.Active'),
                        'Inactive' => __('products.statuses.Inactive'),
                        'Discontinued' => __('products.statuses.Discontinued'),
                    ]),
                SelectFilter::make('type')
                    ->label(__('products.filters.type'))
                    ->options([
                        'Service' => __('products.types.Service'),
                        'Physical' => __('products.types.Physical'),
                    ]),
                TrashedFilter::make()
                    ->label(__('products.filters.trashed')),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->label(__('products.actions.delete')),
                    ForceDeleteBulkAction::make()
                        ->label(__('products.actions.force_delete')),
                    RestoreBulkAction::make()
                        ->label(__('products.actions.restore')),
                ]),
            ]);
    }
}
