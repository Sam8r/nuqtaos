<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Modules\Categories\Models\Category;
use Modules\Settings\Models\Setting;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        $currencies = json_decode(
            File::get(
                base_path('vendor/umpirsky/currency-list/data/en/currency.json')
            ),
            true
        );

        $defaultCurrency = Setting::first()?->currency;

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
                    ->maxLength(255)
                    ->unique(ignoreRecord: true)
                    ->default(fn () => 'SKU-' . strtoupper(Str::random(6))),

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

                Grid::make(2)
                    ->schema([
                        TextInput::make('price')
                            ->label('Price')
                            ->numeric()
                            ->minValue(1)
                            ->required(),

                        Select::make('currency')
                            ->label('Currency')
                            ->options($currencies)
                            ->default($defaultCurrency)
                            ->searchable()
                            ->required(),
                    ]),

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

                FileUpload::make('barcode_path')
                    ->label('Barcode')
                    ->image()
                    ->disk('public')
                    ->directory('barcodes')
                    ->visibility('public'),

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
