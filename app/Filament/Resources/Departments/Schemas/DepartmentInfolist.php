<?php

namespace App\Filament\Resources\Departments\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class DepartmentInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name')
                    ->label(__('departments.fields.name')),
                TextEntry::make('parent.name')
                    ->label(__('departments.fields.parent'))
                    ->state(fn ($record) => $record->parent?->name)
                    ->placeholder(__('departments.messages.no_parent'))
                    ->url(fn ($record) => $record->parent ? route('filament.admin.resources.departments.view', $record->parent) : null
                    ),
            ]);
    }
}
