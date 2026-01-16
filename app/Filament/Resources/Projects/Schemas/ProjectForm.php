<?php

namespace App\Filament\Resources\Projects\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Slider;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Modules\Settings\Models\Setting;

class ProjectForm
{
    public static function configure(Schema $schema): Schema
    {
        $currency = Setting::value('currency');

        return $schema
            ->components([
                TextInput::make('name')
                    ->label(__('projects.fields.name'))
                    ->required()
                    ->maxLength(255),

                Select::make('client_id')
                    ->label(__('projects.fields.client'))
                    ->relationship('client', 'company_name')
                    ->searchable()
                    ->preload()
                    ->required(),

                Select::make('team_leader_id')
                    ->label(__('projects.fields.team_leader'))
                    ->relationship('teamLeader', 'name')
                    ->searchable()
                    ->preload(),

                Select::make('status')
                    ->label(__('projects.fields.status'))
                    ->options([
                        'Active' => __('projects.statuses.Active'),
                        'Completed' => __('projects.statuses.Completed'),
                        'On Hold' => __('projects.statuses.On Hold'),
                    ])
                    ->default('Active')
                    ->required(),

                DatePicker::make('start_date')
                    ->label(__('projects.fields.start_date')),

                DatePicker::make('end_date')
                    ->label(__('projects.fields.end_date')),

                TextInput::make('budget')
                    ->label(__('projects.fields.budget'))
                    ->numeric()
                    ->prefix($currency),

                Slider::make('progress_percentage')
                    ->label(__('projects.fields.progress_percentage'))
                    ->step(5)
                    ->default(0),

                Textarea::make('description')
                    ->label(__('projects.fields.description'))
                    ->rows(3),
            ]);
    }
}
