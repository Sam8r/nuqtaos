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
                    ->label('Project Name')
                    ->searchable(),

                TextColumn::make('client.company_name')
                    ->label('Client'),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Active' => 'success',
                        'Completed' => 'info',
                        'On Hold' => 'warning',
                    }),

                TextColumn::make('progress_percentage')
                    ->label('Progress')
                    ->formatStateUsing(fn ($state) => ($state ?? 0) . '%')
                    ->sortable()
                    ->alignCenter(),

                TextColumn::make('budget')
                    ->label('Budget')
                    ->money($currency)
                    ->sortable(),

                TextColumn::make('end_date')
                    ->label('Deadline')
                    ->date()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'Active' => 'Active',
                        'Completed' => 'Completed',
                        'On Hold' => 'On Hold',
                    ]),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
