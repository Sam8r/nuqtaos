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
                    ->label(__('expense_categories.fields.name_en'))
                    ->required()
                    ->maxLength(255),
                TextInput::make('name.ar')
                    ->label(__('expense_categories.fields.name_ar'))
                    ->required()
                    ->maxLength(255),
            ]);
    }
}
