<?php

namespace App\Filament\Resources\Clients\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Parfaitementweb\FilamentCountryField\Forms\Components\Country;

class ClientForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('company_name.en')
                    ->label('Company Name (EN)')
                    ->required()
                    ->maxLength(255),
                TextInput::make('company_name.ar')
                    ->label('Company Name (AR)')
                    ->required()
                    ->maxLength(255),
                TextInput::make('contact_person_details')
                    ->label('Contact Person Details')
                    ->maxLength(255),
                TextInput::make('address')
                    ->label('Address')
                    ->required()
                    ->maxLength(255),
                Repeater::make('emails')
                    ->relationship('emails')
                    ->schema([
                        TextInput::make('email')
                            ->email()
                            ->required()
                            ->rules([
                                fn ($record) => [
                                    Rule::unique('client_emails', 'email')->ignore($record?->id),
                                    'email',
                                ],
                            ])
                    ])
                    ->label('Emails')
                    ->columns(1),
                Repeater::make('phones')
                    ->relationship('phones')
                    ->schema([
                        TextInput::make('phone')
                            ->required()
                            ->rules([
                                fn ($record) => [
                                    Rule::unique('client_phones', 'phone')->ignore($record?->id),
                                    'regex:/^\+?\d{7,15}$/',
                                ],
                            ])
                    ])
                    ->label('Phones')
                    ->columns(1),
                TextInput::make('tax_number')
                    ->label('Tax Number')
                    ->maxLength(255),
                FileUpload::make('registration_documents')
                    ->label('Registration Documents')
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
                    ->helperText('Allowed file types: jpg, jpeg, png, webp, pdf, doc, docx.'),
                TextInput::make('credit_limit')
                    ->label('Credit Limit')
                    ->numeric()
                    ->required(),
                TextInput::make('payment_terms.en')
                    ->label('Payment Terms (EN)')
                    ->maxLength(255),
                TextInput::make('payment_terms.ar')
                    ->label('Payment Terms (AR)')
                    ->maxLength(255),
                TextInput::make('industry_type.en')
                    ->label('Industry Type (EN)')
                    ->maxLength(255),
                TextInput::make('industry_type.ar')
                    ->label('Industry Type (AR)')
                    ->maxLength(255),
                Select::make('status')
                    ->label('Status')
                    ->options([
                        'Active' => 'Active',
                        'Inactive' => 'Inactive',
                        'Pending' => 'Pending',
                    ])
                    ->required(),
                Select::make('tier')
                    ->label('Tier')
                    ->options([
                        'Gold' => 'Gold',
                        'Silver' => 'Silver',
                        'Bronze' => 'Bronze',
                    ])
                    ->required(),
                Country::make('country')
                    ->required(),
        ]);
    }
}
