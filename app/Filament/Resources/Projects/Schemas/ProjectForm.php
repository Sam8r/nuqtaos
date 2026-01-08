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
                    ->label('Project Name')
                    ->required()
                    ->maxLength(255),

                Select::make('client_id')
                    ->label('Client')
                    ->relationship('client', 'company_name')
                    ->searchable()
                    ->preload()
                    ->required(),

                Select::make('team_leader_id')
                    ->label('Project Manager')
                    ->relationship('teamLeader', 'name')
                    ->searchable()
                    ->preload(),

                Select::make('status')
                    ->options([
                        'Active' => 'Active',
                        'Completed' => 'Completed',
                        'On Hold' => 'On Hold',
                    ])
                    ->default('Active')
                    ->required(),

                DatePicker::make('start_date')
                    ->label('Start Date'),

                DatePicker::make('end_date')
                    ->label('End Date'),

                TextInput::make('budget')
                    ->numeric()
                    ->prefix($currency),

                Slider::make('progress_percentage')
                    ->label('Progress (%)')
                    ->step(5)
                    ->default(0),

                Textarea::make('description')
                    ->label('Description')
                    ->rows(3),
            ]);
    }
}
