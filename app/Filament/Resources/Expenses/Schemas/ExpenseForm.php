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
                    ->label(__('expenses.fields.category'))
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),

                TextInput::make('amount')
                    ->label(__('expenses.fields.amount'))
                    ->numeric()
                    ->required(),

                DatePicker::make('expense_date')
                    ->label(__('expenses.fields.expense_date'))
                    ->required(),

                Textarea::make('description')
                    ->label(__('expenses.fields.description')),

                FileUpload::make('documents')
                    ->label(__('expenses.fields.documents'))
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
                    ->helperText(__('expenses.helper_texts.documents'))
                    ->columnSpanFull(),

                Hidden::make('submitted_by')
                    ->default(fn () => auth()->id()),
                
                Hidden::make('status')
                    ->default('Pending'),
            ]);
    }
}
