<?php

namespace App\Filament\Resources\Categories\Schemas;

use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;
use Hugomyb\FilamentMediaAction\Actions\MediaAction;

class CategoryInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name')
                    ->label(__('categories.fields.name')),
                TextEntry::make('description')
                    ->label(__('categories.fields.description')),
                ImageEntry::make('image_path')
                    ->label(__('categories.fields.image'))
                    ->disk('public')
                    ->getStateUsing(fn ($record) => $record->image_path
                        ? asset('storage/' . $record->image_path)
                        : null)
                    ->square()
                    ->size(200)
                    ->extraImgAttributes([
                        'class' => 'cursor-pointer hover:opacity-80 transition-opacity',
                    ])
                    ->action(
                        MediaAction::make('view')
                            ->media(fn ($state) => $state)
                            ->modalHeading(__('categories.modals.view_image'))
                            ->slideOver()
                    )
                    ->placeholder(__('categories.messages.no_image')),
                TextEntry::make('priority')
                    ->label(__('categories.fields.priority'))
                    ->formatStateUsing(fn ($state) => $state ? __('categories.priorities.' . $state) : null),
            ]);
    }
}
