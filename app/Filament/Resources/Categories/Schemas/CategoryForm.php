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
                    ->label(__('categories.fields.name_en'))
                    ->required()
                    ->maxLength(255),

                TextInput::make('name.ar')
                    ->label(__('categories.fields.name_ar'))
                    ->required()
                    ->maxLength(255),

                TextInput::make('description')
                    ->label(__('categories.fields.description'))
                    ->maxLength(255),

                FileUpload::make('image_path')
                    ->label(__('categories.fields.image'))
                    ->image()
                    ->directory('categories')
                    ->disk('public')
                    ->visibility('public'),

                Select::make('priority')
                    ->label(__('categories.fields.priority'))
                    ->options([
                        1 => __('categories.priorities.1'),
                        2 => __('categories.priorities.2'),
                        3 => __('categories.priorities.3'),
                    ])
                    ->default(1),
            ]);
    }
}
