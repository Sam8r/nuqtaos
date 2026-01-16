<?php

namespace App\Filament\Resources\Clients\Schemas;

use App\Filament\Infolists\Components\AttachmentsEntry;
use Filament\Actions\Action;
use Filament\Forms\Components\Repeater;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\SpatieMediaLibraryImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ViewEntry;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Hugomyb\FilamentMediaAction\Actions\MediaAction;
use Illuminate\Support\Facades\Storage;
use Parfaitementweb\FilamentCountryField\Infolists\Components\CountryEntry;

class ClientInfolist
{
    public static function configure(Schema $schema): Schema
    {
        $locale = strtolower(str_replace('_', '-', app()->getLocale() ?? 'en'));
        $primaryLocale = explode('-', $locale)[0];

        $countryPath = base_path("vendor/umpirsky/country-list/data/{$primaryLocale}/country.php");

        if (! file_exists($countryPath)) {
            $countryPath = base_path('vendor/umpirsky/country-list/data/en/country.php');
        }

        $countries = require $countryPath;

        return $schema
            ->components([
                TextEntry::make('company_name')
                    ->label(__('clients.fields.company_name')),
                TextEntry::make('contact_person_details')
                    ->label(__('clients.fields.contact_person_details')),
                TextEntry::make('address')
                    ->label(__('clients.fields.address')),
                TextEntry::make('emails')
                    ->label(__('clients.fields.emails'))
                    ->state(fn ($record) => $record->emails->pluck('email')->join(', ')),
                TextEntry::make('phones')
                    ->label(__('clients.fields.phones'))
                    ->state(fn ($record) => $record->phones->pluck('phone')->join(', ')),
                TextEntry::make('tax_number')
                    ->label(__('clients.fields.tax_number')),
                RepeatableEntry::make('registration_documents_images')
                    ->label(__('clients.document_labels.images'))
                    ->state(fn ($record) => collect($record->registration_documents ?? [])
                        ->filter(fn ($file) => preg_match('/\.(jpg|jpeg|png|webp)$/i', $file))
                        ->map(fn ($file) => [
                            'url' => asset('storage/' . $file),
                            'name' => basename($file)
                        ])
                        ->values()
                        ->toArray()
                    )
                    ->schema([
                        ImageEntry::make('url')
                            ->label(__('clients.document_labels.image'))
                            ->square()
                            ->size(200)
                            ->extraImgAttributes(['class' => 'cursor-pointer hover:opacity-80 transition-opacity'])
                            ->action(
                                MediaAction::make('view')
                                    ->media(fn ($state) => $state)
                                    ->modalHeading(__('clients.modals.view_document_image'))
                                    ->slideOver()
                            ),
                        TextEntry::make('name')
                            ->label(__('clients.document_labels.file_name'))
                            ->color('gray'),
                    ])
                    ->grid(2) // Display 3 items per row
                    ->columnSpan(1), // Takes half the available width
                Section::make(__('clients.sections.registration_documents'))
                    ->schema([
                        TextEntry::make('registration_documents_list')
                            ->label(__('clients.document_labels.files'))
                            ->state(fn ($record) => collect($record->registration_documents ?? [])
                                ->filter(fn ($file) => preg_match('/\.(pdf|doc|docx)$/i', $file))
                                ->map(fn ($file) => __(
                                    'clients.messages.document_link',
                                    [
                                        'url' => asset('storage/' . $file),
                                        'name' => basename($file),
                                    ]
                                ))
                                ->join('')
                            )
                            ->html()
                            ->placeholder(__('clients.messages.no_documents')),
                    ]),
                TextEntry::make('credit_limit')
                    ->label(__('clients.fields.credit_limit'))
                    ->money(fn ($record) => $record->credit_currency),
                TextEntry::make('payment_terms')
                    ->label(__('clients.fields.payment_terms')),
                TextEntry::make('industry_type')
                    ->label(__('clients.fields.industry_type')),
                TextEntry::make('status')
                    ->label(__('clients.fields.status'))
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => __('clients.statuses.' . $state)),
                TextEntry::make('tier')
                    ->label(__('clients.fields.tier'))
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => __('clients.tiers.' . $state)),
                CountryEntry::make('country')
                    ->label(__('clients.fields.country'))
                    ->formatStateUsing(fn ($state) => $countries[$state] ?? $state)
                    ->color('primary'),
            ]);
    }
}
