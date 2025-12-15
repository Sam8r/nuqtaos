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
                    ->label('Name'),
                TextEntry::make('description')
                    ->label('Description'),
                ImageEntry::make('image_path')
                    ->label('Category Image')
                    ->disk('public')
                    ->getStateUsing(fn ($record) => asset('storage/' . $record->image_path))
                    ->square()
                    ->size(200)
                    ->extraImgAttributes([
                        'class' => 'cursor-pointer hover:opacity-80 transition-opacity',
                    ])
                    ->action(
                        MediaAction::make('view')
                            ->media(fn ($state) => $state)
                            ->modalHeading('View Category Image')
                            ->slideOver()
                    ),
                TextEntry::make('priority')
                    ->label('Priority'),
            ]);
    }
}
