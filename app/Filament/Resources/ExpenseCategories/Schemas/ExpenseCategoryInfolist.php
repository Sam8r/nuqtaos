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
                TextEntry::make('name')
                    ->label(__('expense_categories.fields.name')),
                TextEntry::make('parent.name')
                    ->label(__('expense_categories.fields.parent'))
                    ->state(fn ($record) => $record->parent?->getTranslation('name', app()->getLocale()))
                    ->placeholder(__('expense_categories.messages.no_parent'))
                    ->url(fn ($record) => $record->parent ? route('filament.admin.resources.expense-categories.view', $record->parent) : null),
            ]);
    }
}
