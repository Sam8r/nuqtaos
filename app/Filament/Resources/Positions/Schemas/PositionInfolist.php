<?php

namespace App\Filament\Resources\Positions\Schemas;

use App\Filament\Custom\TextEntry;
use Filament\Schemas\Schema;

class PositionInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name')
                    ->label(__('positions.fields.name')),
                TextEntry::make('department.name')
                    ->label(__('positions.fields.department'))
                    ->state(fn ($record) => $record->department?->name)
                    ->placeholder(__('positions.messages.no_department'))
                    ->url(fn ($record) => $record->department ? route('filament.admin.resources.departments.view', $record->department) : null),
            ]);
    }
}
