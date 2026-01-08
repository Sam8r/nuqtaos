<?php

namespace App\Filament\Resources\ExpenseCategories\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ExpenseCategoryForm
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
