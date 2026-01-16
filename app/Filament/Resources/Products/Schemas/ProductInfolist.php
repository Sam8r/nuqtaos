<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ViewEntry;
use Filament\Schemas\Schema;
use Hugomyb\FilamentMediaAction\Actions\MediaAction;

class ProductInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('code')
                    ->label(__('products.fields.code')),
                TextEntry::make('sku')
                    ->label(__('products.fields.sku')),
                TextEntry::make('name')
                    ->label(__('products.fields.name')),
                TextEntry::make('description')
                    ->label(__('products.fields.description')),
                TextEntry::make('price')
                    ->label(__('products.fields.price'))
                    ->money(fn($record) => $record->currency),
                TextEntry::make('type')
                    ->label(__('products.fields.type'))
                    ->badge(fn ($state) => match ($state) {
                        'Service' => 'primary',
                        'Physical' => 'success',
                    })
                    ->formatStateUsing(fn ($state) => __('products.types.' . $state)),
                TextEntry::make('unit')
                    ->label(__('products.fields.unit')),
                RepeatableEntry::make('images')
                    ->label(__('products.fields.images'))
                    ->state(fn ($record) => collect($record->images ?? [])
                        ->map(fn ($image) => [
                            'url' => asset('storage/' . $image),
                            'name' => basename($image),
                        ])
                        ->values()
                        ->toArray()
                    )
                    ->schema([
                        ImageEntry::make('url')
                            ->label(__('products.labels.image'))
                            ->square()
                            ->size(200)
                            ->extraImgAttributes(['class' => 'cursor-pointer hover:opacity-80 transition-opacity'])
                            ->action(
                                MediaAction::make('view')
                                    ->media(fn ($state) => $state)
                                    ->modalHeading(__('products.modals.view_image'))
                                    ->slideOver()
                            ),
                    ])
                    ->grid(2)
                    ->columnSpan(1),
                ImageEntry::make('barcode_path')
                    ->label(__('products.fields.barcode'))
                    ->extraImgAttributes(['style' => 'width:200px; height:auto;'])
                    ->getStateUsing(fn ($record) => asset('storage/' . $record->barcode_path))
                    ->action(
                        MediaAction::make('view')
                            ->media(fn ($state) => $state)
                            ->modalHeading(__('products.modals.view_barcode'))
                            ->slideOver()
                    ),
                TextEntry::make('category.name')
                    ->label(__('products.fields.category')),
                TextEntry::make('status')
                    ->label(__('products.fields.status'))
                    ->badge(fn ($state) => match ($state) {
                        'Active' => 'success',
                        'Inactive' => 'warning',
                        'Discontinued' => 'danger',
                    })
                    ->formatStateUsing(fn ($state) => __('products.statuses.' . $state)),
            ]);
    }
}
