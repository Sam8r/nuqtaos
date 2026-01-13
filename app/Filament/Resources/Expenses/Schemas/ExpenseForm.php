<?php

namespace App\Filament\Resources\Expenses\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ExpenseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('expense_category_id')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),

                TextInput::make('amount')
                    ->numeric()
                    ->required(),

                DatePicker::make('expense_date')
                    ->required(),

                Textarea::make('description'),

                FileUpload::make('documents')
                    ->label('Supporting Documents')
                    ->multiple()
                    ->directory('expense_docs')
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
                    ->helperText('Upload receipts, invoices, or other supporting documents. Allowed file types: jpg, jpeg, png, webp, pdf, doc, docx.')
                    ->columnSpanFull(),

                Hidden::make('submitted_by')
                    ->default(fn () => auth()->id()),
                
                Hidden::make('status')
                    ->default('Pending'),
            ]);
    }
}
