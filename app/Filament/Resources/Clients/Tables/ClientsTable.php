<?php

namespace App\Filament\Resources\Clients\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Parfaitementweb\FilamentCountryField\Tables\Columns\CountryColumn;

class ClientsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('company_name')
                    ->label('Company Name')
                    ->searchable(),
                TextColumn::make('emails.email')
                    ->label('Emails')
                    ->wrap()
                    ->searchable(),
                TextColumn::make('phones.phone')
                    ->label('Phones')
                    ->wrap()
                    ->searchable(),
                TextColumn::make('industry_type')
                    ->label('Industry Type')
                    ->searchable(),
                TextColumn::make('status')
                    ->badge()
                    ->searchable(),
                TextColumn::make('tier')
                    ->badge()
                    ->searchable(),
                CountryColumn::make('country'),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'Active' => 'Active',
                        'Inactive' => 'Inactive',
                        'Pending' => 'Pending',
                    ])
                    ->label('Status'),

                SelectFilter::make('tier')
                    ->options([
                        'Gold' => 'Gold',
                        'Silver' => 'Silver',
                        'Bronze' => 'Bronze',
                    ])
                    ->label('Tier'),
                SelectFilter::make('country')
                    ->label('Country')
                    ->options(fn () => require base_path('vendor/umpirsky/country-list/data/en/country.php')),
                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
