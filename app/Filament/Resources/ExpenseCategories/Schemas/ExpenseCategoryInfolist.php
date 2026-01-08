<?php

namespace App\Filament\Resources\ExpenseCategories\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ExpenseCategoryInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name'),
                TextEntry::make('parent.name')
                    ->label('Parent Expense Category')
                    ->url(fn ($record) => $record->parent ? route('filament.admin.resources.expense-categories.view', $record->parent) : null
                    ),
            ]);
    }
}
