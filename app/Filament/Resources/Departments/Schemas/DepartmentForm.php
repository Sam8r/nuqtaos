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
                    ->label(__('departments.fields.name_en'))
                    ->required()
                    ->maxLength(255),
                TextInput::make('name.ar')
                    ->label(__('departments.fields.name_ar'))
                    ->required()
                    ->maxLength(255),
            ]);
    }
}
