<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;
use Modules\Categories\Models\Category;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('code')
                    ->label('Product Code')
                    ->maxLength(255)
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->default(fn () => 'PRD-' . strtoupper(Str::random(6))),

                TextInput::make('sku')
                    ->label('SKU')
                    ->unique(ignoreRecord: true),

                TextInput::make('name.en')
                    ->label('Name (EN)')
                    ->maxLength(255)
                    ->required(),

                TextInput::make('name.ar')
                    ->label('Name (AR)')
                    ->maxLength(255)
                    ->required(),

                Textarea::make('description.en')
                    ->label('Description (EN)')
                    ->rows(3),

                Textarea::make('description.ar')
                    ->label('Description (AR)')
                    ->rows(3),

                TextInput::make('price')
                    ->label('Price')
                    ->numeric()
                    ->minValue(1)
                    ->required(),

                Select::make('type')
                    ->label('Type')
                    ->options([
                        'Service' => 'Service',
                        'Physical' => 'Physical',
                    ])
                    ->required(),

                TextInput::make('unit')
                    ->label('Unit')
                    ->placeholder('e.g. piece, kg'),

                TextInput::make('qr_value')
                    ->label('QR Code')
                    ->placeholder('Enter manually or click Generate')
                    ->reactive()
                    ->suffixAction(
                        Action::make('generate')
                            ->label('Generate')
                            ->action(function ($record, $livewire, $get, $set) {
                                $set('qr_value', (string) Str::uuid());
                            })
                            ->button()
                    ),

                FileUpload::make('images')
                    ->label('Product Images')
                    ->multiple()
                    ->image()
                    ->reorderable()
                    ->disk('public')
                    ->visibility('public'),
                Select::make('status')
                    ->label('Status')
                    ->options([
                        'Active' => 'Active',
                        'Inactive' => 'Inactive',
                        'Discontinued' => 'Discontinued',
                    ])
                    ->default('active')
                    ->required(),

                Select::make('category_id')
                    ->label('Category')
                    ->relationship('category', 'name')
                    ->required()
                    ->preload()
                    ->createOptionForm([
                        TextInput::make('name')
                            ->label('Category Name')
                            ->required()
                            ->maxLength(255),
                    ])
                    ->createOptionUsing(function ($data) {
                        // This is called when the user creates a new category
                        return Category::create([
                            'name' => $data['name'],
                            'sort_order' => 1, // default value if needed
                        ])->id;
                    })
        ]);
    }
}
