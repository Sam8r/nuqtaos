<?php

namespace App\Filament\Resources\Positions\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PositionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name.en')
                    ->label(__('positions.fields.name_en'))
                    ->required()
                    ->maxLength(255),
                TextInput::make('name.ar')
                    ->label(__('positions.fields.name_ar'))
                    ->required()
                    ->maxLength(255),
                Select::make('department_id')
                    ->label(__('positions.fields.department'))
                    ->relationship('department', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
            ]);
    }
}
