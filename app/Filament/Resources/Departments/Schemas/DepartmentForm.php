<?php

namespace App\Filament\Resources\Departments\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class DepartmentForm
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
            ]);
    }
}
