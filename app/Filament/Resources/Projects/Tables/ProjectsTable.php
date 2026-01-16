<?php

namespace App\Filament\Resources\Projects\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Modules\Settings\Models\Setting;

class ProjectsTable
{
    public static function configure(Table $table): Table
    {
        $currency = Setting::value('currency');

        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('projects.fields.name'))
                    ->searchable(),

                TextColumn::make('client.company_name')
                    ->label(__('projects.fields.client_name')),

                TextColumn::make('status')
                    ->label(__('projects.fields.status'))
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Active' => 'success',
                        'Completed' => 'info',
                        'On Hold' => 'warning',
                    })
                    ->formatStateUsing(fn (string $state): string => __('projects.statuses.' . $state)),

                TextColumn::make('progress_percentage')
                    ->label(__('projects.fields.progress_percentage'))
                    ->formatStateUsing(fn ($state) => __('projects.messages.progress_with_value', ['value' => $state ?? 0]))
                    ->sortable()
                    ->alignCenter(),

                TextColumn::make('budget')
                    ->label(__('projects.fields.budget'))
                    ->money($currency)
                    ->sortable(),

                TextColumn::make('end_date')
                    ->label(__('projects.fields.deadline'))
                    ->date()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label(__('projects.filters.status'))
                    ->options([
                        'Active' => __('projects.statuses.Active'),
                        'Completed' => __('projects.statuses.Completed'),
                        'On Hold' => __('projects.statuses.On Hold'),
                    ]),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->label(__('projects.actions.delete')),
                ]),
            ]);
    }
}
