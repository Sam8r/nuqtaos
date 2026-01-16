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
                TextInput::make('code')
                    ->label(__('products.fields.code'))
                    ->maxLength(255)
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->default(fn () => 'PRD-' . strtoupper(Str::random(6))),

                TextInput::make('sku')
                    ->label(__('products.fields.sku'))
                    ->maxLength(255)
                    ->unique(ignoreRecord: true)
                    ->default(fn () => 'SKU-' . strtoupper(Str::random(6))),

                TextInput::make('name.en')
                    ->label(__('products.fields.name_en'))
                    ->maxLength(255)
                    ->required(),

                TextInput::make('name.ar')
                    ->label(__('products.fields.name_ar'))
                    ->maxLength(255)
                    ->required(),

                Textarea::make('description.en')
                    ->label(__('products.fields.description_en'))
                    ->rows(3),

                Textarea::make('description.ar')
                    ->label(__('products.fields.description_ar'))
                    ->rows(3),

                Grid::make(2)
                    ->schema([
                        TextInput::make('price')
                            ->label(__('products.fields.price'))
                            ->numeric()
                            ->minValue(1)
                            ->required(),

                        Select::make('currency')
                            ->label(__('products.fields.currency'))
                            ->options($currencies)
                            ->default($defaultCurrency)
                            ->searchable()
                            ->required(),
                    ]),

                Select::make('type')
                    ->label(__('products.fields.type'))
                    ->options([
                        'Service' => __('products.types.Service'),
                        'Physical' => __('products.types.Physical'),
                    ])
                    ->required(),

                TextInput::make('unit')
                    ->label(__('products.fields.unit'))
                    ->placeholder(__('products.placeholders.unit')),

                FileUpload::make('barcode_path')
                    ->label(__('products.fields.barcode'))
                    ->image()
                    ->disk('public')
                    ->directory('barcodes')
                    ->visibility('public'),

                FileUpload::make('images')
                    ->label(__('products.fields.images'))
                    ->multiple()
                    ->image()
                    ->reorderable()
                    ->disk('public')
                    ->visibility('public'),

                Select::make('status')
                    ->label(__('products.fields.status'))
                    ->options([
                        'Active' => __('products.statuses.Active'),
                        'Inactive' => __('products.statuses.Inactive'),
                        'Discontinued' => __('products.statuses.Discontinued'),
                    ])
                    ->default('active')
                    ->required(),

                Select::make('category_id')
                    ->label(__('products.fields.category'))
                    ->relationship('category', 'name')
                    ->required()
                    ->preload()
                    ->createOptionForm([
                        TextInput::make('name')
                            ->label(__('products.fields.name'))
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
