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
                TextEntry::make('code')->label('Product Code'),
                TextEntry::make('sku')
                    ->label('SKU'),
                TextEntry::make('name')
                    ->label('Name'),
                TextEntry::make('description')
                    ->label('Description'),
                TextEntry::make('price')
                    ->label('Price'),
                TextEntry::make('type')
                    ->label('Type')
                    ->badge(fn ($state) => match ($state) {
                        'Service' => 'primary',
                        'Physical' => 'success',
                    }),
                TextEntry::make('unit')
                    ->label('Unit'),
                RepeatableEntry::make('images')
                    ->label('Product Images')
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
                            ->label('Image')
                            ->square()
                            ->size(200)
                            ->extraImgAttributes(['class' => 'cursor-pointer hover:opacity-80 transition-opacity'])
                            ->action(
                                MediaAction::make('view')
                                    ->media(fn ($state) => $state)
                                    ->modalHeading('View Product Image')
                                    ->slideOver()
                            ),
                    ])
                    ->grid(2)
                    ->columnSpan(1),
                ImageEntry::make('barcode_path')
                    ->label('Barcode')
                    ->extraImgAttributes(['style' => 'width:200px; height:auto;'])                    ->getStateUsing(fn ($record) => asset('storage/' . $record->barcode_path))
                    ->action(
                        MediaAction::make('view')
                            ->media(fn ($state) => $state)
                            ->modalHeading('View Barcode')
                            ->slideOver()
                    ),
                TextEntry::make('category.name')->label('Category'),
                TextEntry::make('status')
                    ->label('Status')
                    ->badge(fn ($state) => match ($state) {
                        'Active' => 'success',
                        'Inactive' => 'warning',
                        'Discontinued' => 'danger',
                    }),
            ]);
    }
}
