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
        return $schema
            ->components([
                TextEntry::make('company_name')->label('Company Name'),
                TextEntry::make('contact_person_details')->label('Contact Person Details'),
                TextEntry::make('address')->label('Address'),
                TextEntry::make('emails')
                    ->label('Emails')
                    ->state(fn ($record) => $record->emails->pluck('email')->join(', ')),
                TextEntry::make('phones')
                    ->label('Phones')
                    ->state(fn ($record) => $record->phones->pluck('phone')->join(', ')),
                TextEntry::make('tax_number')->label('Tax Number'),
                RepeatableEntry::make('registration_documents_images')
                    ->label('Registration Documents Images')
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
                            ->label('Image')
                            ->square()
                            ->size(200)
                            ->extraImgAttributes(['class' => 'inline-block cursor-pointer hover:opacity-80 transition-opacity'])
                            ->action(
                                MediaAction::make('view')
                                    ->media(fn ($state) => $state)
                                    ->modalHeading('View Image')
                                    ->slideOver()
                            ),
                        TextEntry::make('name')
                            ->label('File Name')
                            ->color('gray'),
                    ])
                    ->grid(2) // Display 3 items per row
                    ->columnSpan(1), // Takes half the available width
                Section::make('Registration Documents')
                    ->schema([
                        TextEntry::make('registration_documents_list')
                            ->label('Documents')
                            ->state(fn ($record) => collect($record->registration_documents ?? [])
                                ->filter(fn ($file) => preg_match('/\.(pdf|doc|docx)$/i', $file))
                                ->map(fn ($file) => sprintf(
                                    '<a href="%s" target="_blank" class="text-primary-600 hover:text-primary-800 hover:underline font-medium block mb-1">%s <br></a>',
                                    asset('storage/' . $file),
                                    basename($file)
                                ))
                                ->join('')
                            )
                            ->html()
                            ->placeholder('No documents available'),
                    ]),

                TextEntry::make('credit_limit')->label('Credit Limit')->money('EGP'),
                TextEntry::make('payment_terms')->label('Payment Terms'),
                TextEntry::make('industry_type')->label('Industry Type'),
                TextEntry::make('status')->label('Status')->badge(),
                TextEntry::make('tier')->label('Tier')->badge(),
                CountryEntry::make('country')->label('Country')->color('primary'),
            ]);
    }
}
