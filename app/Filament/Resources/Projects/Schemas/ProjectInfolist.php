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
                    ->label(__('projects.fields.name'))
                    ->weight('bold')
                    ->size('lg'),

                TextEntry::make('client.company_name')
                    ->label(__('projects.fields.client_name')),

                TextEntry::make('teamLeader.name')
                    ->label(__('projects.fields.team_leader')),

                TextEntry::make('status')
                    ->label(__('projects.fields.status'))
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Active' => 'success',
                        'Completed' => 'info',
                        'On Hold' => 'warning',
                    })
                    ->formatStateUsing(fn (string $state): string => __('projects.statuses.' . $state)),

                TextEntry::make('start_date')
                    ->label(__('projects.fields.start_date'))
                    ->date(),

                TextEntry::make('end_date')
                    ->label(__('projects.fields.deadline'))
                    ->date(),

                TextEntry::make('budget')
                    ->label(__('projects.fields.budget'))
                    ->money($currency),

                TextEntry::make('progress_percentage')
                    ->label(__('projects.fields.progress_percentage'))
                    ->formatStateUsing(fn ($state) => __('projects.messages.progress_with_value', ['value' => $state ?? 0])),

                TextEntry::make('description')
                    ->label(__('projects.fields.description')),
            ]);
    }
}
