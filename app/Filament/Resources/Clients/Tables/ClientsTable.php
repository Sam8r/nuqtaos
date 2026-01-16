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
        $locale = strtolower(str_replace('_', '-', app()->getLocale() ?? 'en'));
        $primaryLocale = explode('-', $locale)[0];

        $countryPath = base_path("vendor/umpirsky/country-list/data/{$primaryLocale}/country.php");

        if (! file_exists($countryPath)) {
            $countryPath = base_path('vendor/umpirsky/country-list/data/en/country.php');
        }

        $countries = require $countryPath;
        $englishCountries = require base_path('vendor/umpirsky/country-list/data/en/country.php');

        return $table
            ->columns([
                TextColumn::make('company_name')
                    ->label(__('clients.fields.company_name'))
                    ->searchable(),
                TextColumn::make('emails.email')
                    ->label(__('clients.fields.emails'))
                    ->wrap()
                    ->searchable(),
                TextColumn::make('phones.phone')
                    ->label(__('clients.fields.phones'))
                    ->wrap()
                    ->searchable(),
                TextColumn::make('industry_type')
                    ->label(__('clients.fields.industry_type'))
                    ->searchable(),
                TextColumn::make('status')
                    ->label(__('clients.fields.status'))
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => __('clients.statuses.' . $state))
                    ->searchable(),
                TextColumn::make('tier')
                    ->label(__('clients.fields.tier'))
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => __('clients.tiers.' . $state))
                    ->searchable(),
                CountryColumn::make('country')
                    ->label(__('clients.fields.country'))
                    ->formatStateUsing(function ($state) use ($countries, $englishCountries) {
                        if ($state === null || $state === '') {
                            return $state;
                        }

                        $state = trim($state);

                        return $countries[$state]
                            ?? ($countries[strtoupper($state)] ?? null)
                            ?? (function () use ($countries, $englishCountries, $state) {
                                $code = array_search($state, $englishCountries, true);

                                if ($code !== false && isset($countries[$code])) {
                                    return $countries[$code];
                                }

                                return $state;
                            })();
                    }),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'Active' => __('clients.statuses.Active'),
                        'Inactive' => __('clients.statuses.Inactive'),
                        'Pending' => __('clients.statuses.Pending'),
                    ])
                    ->label(__('clients.filters.status')),

                SelectFilter::make('tier')
                    ->options([
                        'Gold' => __('clients.tiers.Gold'),
                        'Silver' => __('clients.tiers.Silver'),
                        'Bronze' => __('clients.tiers.Bronze'),
                    ])
                    ->label(__('clients.filters.tier')),
                SelectFilter::make('country')
                    ->label(__('clients.filters.country'))
                    ->options(fn () => require base_path('vendor/umpirsky/country-list/data/en/country.php')),
                TrashedFilter::make()
                    ->label(__('clients.filters.trashed')),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->label(__('clients.actions.delete')),
                    ForceDeleteBulkAction::make()
                        ->label(__('clients.actions.force_delete')),
                    RestoreBulkAction::make()
                        ->label(__('clients.actions.restore')),
                ]),
            ]);
    }
}
