<?php

namespace App\Filament\Resources\Categories\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name.en')
                    ->label('Name (EN)')
                    ->required()
                    ->maxLength(255),

                TextInput::make('name.ar')
                    ->label('Name (AR)')
                    ->required()
                    ->maxLength(255),

                TextInput::make('description')
                    ->label('Description')
                    ->maxLength(255),

                FileUpload::make('image_path')
                    ->label('Category Image')
                    ->image()
                    ->directory('categories')
                    ->disk('public')
                    ->visibility('public'),

                Select::make('priority')
                    ->label('Priority')
                    ->options([
                        1 => 'Important',
                        2 => 'Main',
                        3 => 'Secondary',
                    ])
                    ->default(1),
            ]);
    }
}
