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
                TextEntry::make('name'),
                TextEntry::make('department.name')
                    ->label('Department')
                    ->url(fn ($record) => route('filament.admin.resources.departments.view', $record->department->id)),
            ]);
    }
}
