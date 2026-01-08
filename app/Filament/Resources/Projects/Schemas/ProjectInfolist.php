<?php

namespace App\Filament\Resources\Projects\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;
use Modules\Settings\Models\Setting;

class ProjectInfolist
{
    public static function configure(Schema $schema): Schema
    {
        $currency = Setting::value('currency');

        return $schema
            ->components([
                TextEntry::make('name')
                    ->label('Project Name')
                    ->weight('bold')
                    ->size('lg'),

                TextEntry::make('client.company_name')
                    ->label('Client Name'),

                TextEntry::make('teamLeader.name')
                    ->label('Project Manager'),

                TextEntry::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Active' => 'success',
                        'Completed' => 'info',
                        'On Hold' => 'warning',
                    }),

                TextEntry::make('start_date')
                    ->label('Start Date')
                    ->date(),

                TextEntry::make('end_date')
                    ->label('Deadline')
                    ->date(),

                TextEntry::make('budget')
                    ->label('Budget')
                    ->money($currency),

                TextEntry::make('progress_percentage')
                    ->label('Completion Progress')
                    ->formatStateUsing(fn ($state) => ($state ?? 0) . '%'),

                TextEntry::make('description')
                    ->label('Description'),
            ]);
    }
}
