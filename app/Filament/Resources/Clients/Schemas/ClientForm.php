<?php

namespace App\Filament\Resources\Clients\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Modules\Settings\Models\Setting;
use Parfaitementweb\FilamentCountryField\Forms\Components\Country;

class ClientForm
{
    public static function configure(Schema $schema): Schema
    {
        $locale = strtolower(str_replace('_', '-', app()->getLocale() ?? 'en'));
        $primaryLocale = explode('-', $locale)[0];

        $currencyPath = base_path("vendor/umpirsky/currency-list/data/{$primaryLocale}/currency.json");

        if (! File::exists($currencyPath)) {
            $currencyPath = base_path('vendor/umpirsky/currency-list/data/en/currency.json');
        }

        $currencies = json_decode(File::get($currencyPath), true);

        $defaultCurrency = Setting::first()?->currency;

        return $schema
            ->components([
                TextInput::make('company_name.en')
                    ->label(__('clients.fields.company_name_en'))
                    ->required()
                    ->maxLength(255),
                TextInput::make('company_name.ar')
                    ->label(__('clients.fields.company_name_ar'))
                    ->required()
                    ->maxLength(255),
                TextInput::make('contact_person_details')
                    ->label(__('clients.fields.contact_person_details'))
                    ->maxLength(255),
                TextInput::make('address')
                    ->label(__('clients.fields.address'))
                    ->required()
                    ->maxLength(255),
                Repeater::make('emails')
                    ->relationship('emails')
                    ->schema([
                        TextInput::make('email')
                            ->label(__('clients.fields.email'))
                            ->email()
                            ->required()
                            ->rules([
                                fn ($record) => [
                                    Rule::unique('client_emails', 'email')->ignore($record?->id),
                                    'email',
                                ],
                            ])
                    ])
                    ->label(__('clients.fields.emails'))
                    ->columns(1),
                Repeater::make('phones')
                    ->relationship('phones')
                    ->schema([
                        TextInput::make('phone')
                            ->label(__('clients.fields.phone'))
                            ->required()
                            ->rules([
                                fn ($record) => [
                                    Rule::unique('client_phones', 'phone')->ignore($record?->id),
                                    'regex:/^\+?\d{7,15}$/',
                                ],
                            ])
                    ])
                    ->label(__('clients.fields.phones'))
                    ->columns(1),
                TextInput::make('tax_number')
                    ->label(__('clients.fields.tax_number'))
                    ->maxLength(255),
                FileUpload::make('registration_documents')
                    ->label(__('clients.fields.registration_documents'))
                    ->multiple()
                    ->directory('registration_docs')
                    ->disk('public')
                    ->visibility('public')
                    ->acceptedFileTypes([
                        'image/jpeg',   // jpg, jpeg
                        'image/png',    // png
                        'image/webp',   // webp
                        'application/pdf', // pdf
                        'application/msword', // doc
                        'application/vnd.openxmlformats-officedocument.wordprocessingml.document', // docx
                    ])
                    ->getUploadedFileNameForStorageUsing(function ($file) {
                        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                        $extension = $file->getClientOriginalExtension();

                        return Str::slug($originalName) . '-' . rand(1000, 9999) . '.' . $extension;
                    })
                    ->helperText(__('clients.helper_texts.registration_documents')),
                Grid::make(2)
                    ->schema([
                        TextInput::make('credit_limit')
                            ->label(__('clients.fields.credit_limit'))
                            ->numeric()
                            ->required(),

                        Select::make('credit_currency')
                            ->label(__('clients.fields.credit_currency'))
                            ->options($currencies)
                            ->default($defaultCurrency)
                            ->searchable()
                            ->required(),
                    ]),
                TextInput::make('payment_terms.en')
                    ->label(__('clients.fields.payment_terms_en'))
                    ->maxLength(255),
                TextInput::make('payment_terms.ar')
                    ->label(__('clients.fields.payment_terms_ar'))
                    ->maxLength(255),
                TextInput::make('industry_type.en')
                    ->label(__('clients.fields.industry_type_en'))
                    ->maxLength(255),
                TextInput::make('industry_type.ar')
                    ->label(__('clients.fields.industry_type_ar'))
                    ->maxLength(255),
                Select::make('status')
                    ->label(__('clients.fields.status'))
                    ->options([
                        'Active' => __('clients.statuses.Active'),
                        'Inactive' => __('clients.statuses.Inactive'),
                        'Pending' => __('clients.statuses.Pending'),
                    ])
                    ->required(),
                Select::make('tier')
                    ->label(__('clients.fields.tier'))
                    ->options([
                        'Gold' => __('clients.tiers.Gold'),
                        'Silver' => __('clients.tiers.Silver'),
                        'Bronze' => __('clients.tiers.Bronze'),
                    ])
                    ->required(),
                Country::make('country')
                    ->label(__('clients.fields.country'))
                    ->required(),
        ]);
    }
}
