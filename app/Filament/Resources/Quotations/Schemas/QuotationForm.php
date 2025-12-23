<?php

namespace App\Filament\Resources\Quotations\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;
use Modules\Clients\Models\Client;
use Modules\Products\Models\Product;

class QuotationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Grid::make(3)->schema([
                TextInput::make('number')
                    ->label('Quotation #')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->default(fn () => 'QT-' . now()->format('Ymd') . '-' . Str::upper(Str::random(4))),

                DatePicker::make('issue_date')
                    ->required()
                    ->default(now())
                    ->live(),

                DatePicker::make('valid_until')
                    ->required()
                    ->minDate(fn (callable $get) => $get('issue_date')),

                TextInput::make('tax_value')
                    ->label('Tax Value')
                    ->numeric()
                    ->minValue(0)
                    ->default(0)
                    ->live(debounce: 300)
                    ->disabled(fn (callable $get) => (float) $get('tax_percent') > 0)
                    ->reactive()
                    ->extraInputAttributes([
                        'onfocus' => "if(this.value == '0') this.value='';",
                        'onblur' => "if(this.value == '') this.value='0';",
                    ])
                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                        $set($state !== null && $state !== '' ? (float)$state : 0, null);
                        self::calculateTotals($set, $get);
                    }),

                TextInput::make('tax_percent')
                    ->label('Tax (%)')
                    ->numeric()
                    ->default(0)
                    ->minValue(0)
                    ->maxValue(100)
                    ->dehydrated(fn ($state) => filled($state))
                    ->live(debounce: 300)
                    ->disabled(fn (callable $get) => (float) $get('tax_value') > 0)
                    ->reactive()
                    ->extraInputAttributes([
                        'onfocus' => "if(this.value == '0') this.value='';",
                        'onblur' => "if(this.value == '') this.value='0';",
                    ])
                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                        $set($state !== null && $state !== '' ? (float)$state : 0, null);
                        self::calculateTotals($set, $get);
                    }),

                Select::make('status')
                    ->options([
                        'Draft' => 'Draft',
                        'Sent' => 'Sent',
                        'Under Review' => 'Under Review',
                        'Accepted' => 'Accepted',
                        'Rejected' => 'Rejected',
                    ])
                    ->default('Draft')
                    ->required(),
            ]),

            Select::make('client_id')
                ->relationship('client', 'company_name')
                ->searchable()
                ->preload()
                ->required(),

            Textarea::make('terms_and_conditions')
                ->columnSpanFull(),

            Repeater::make('items')
                ->relationship()
                ->columnSpanFull()
                ->live(debounce: 300)
                ->schema([

                    Select::make('product_id')
                        ->relationship('product', 'name')
                        ->searchable()
                        ->preload()
                        ->live()
                        ->disabled(fn (callable $get) => (bool) $get('custom_name'))
                        ->afterStateUpdated(function ($state, callable $set, callable $get) {
                            $set('quantity', 1);
                            $set('total_price', 0);

                            if (! $state) {
                                $set('description', null);
                                $set('unit_price', 0);

                                self::calculateTotals($set, $get);
                                return;
                            }

                            $product = Product::find($state);

                            if (! $product) {
                                return;
                            }

                            $set('description', $product->description);
                            $set('unit_price', $product->price);

                            self::calculateItemTotal($set, $get);
                            self::calculateTotals($set, $get);
                        }),

                    TextInput::make('custom_name')
                        ->label('Custom Product Name')
                        ->nullable()
                        ->disabled(fn (callable $get) => (bool) $get('product_id')),

                    Textarea::make('description'),

                    Grid::make(5)->schema([
                        TextInput::make('quantity')
                            ->numeric()
                            ->required()
                            ->default(1)
                            ->minValue(1)
                            ->live(debounce: 300)
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                self::calculateItemTotal($set, $get);
                                self::calculateTotals($set, $get);
                            }),

                        TextInput::make('unit_price')
                            ->numeric()
                            ->required()
                            ->live(debounce: 300)
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                self::calculateItemTotal($set, $get);
                                self::calculateTotals($set, $get);
                            }),

                        TextInput::make('discount_value')
                            ->label('Discount Value')
                            ->numeric()
                            ->default(0)
                            ->minValue(0)
                            ->live(debounce: 300)
                            ->disabled(fn (callable $get) => (float) $get('discount_percent') > 0)
                            ->extraInputAttributes([
                                'onfocus' => "if(this.value == '0') this.value='';",
                                'onblur' => "if(this.value == '') this.value='0';",
                            ])
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                $set($state !== null && $state !== '' ? (float)$state : 0, null);
                                self::calculateItemTotal($set, $get);
                                self::calculateTotals($set, $get);
                            }),

                        TextInput::make('discount_percent')
                            ->label('Discount (%)')
                            ->numeric()
                            ->default(0)
                            ->minValue(0)
                            ->maxValue(100)
                            ->live(debounce: 300)
                            ->disabled(fn (callable $get) => (float) $get('discount_value') > 0)
                            ->extraInputAttributes([
                                'onfocus' => "if(this.value == '0') this.value='';",
                                'onblur' => "if(this.value == '') this.value='0';",
                            ])
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                $set($state !== null && $state !== '' ? (float)$state : 0, null);
                                self::calculateItemTotal($set, $get);
                                self::calculateTotals($set, $get);
                            }),

                        TextInput::make('total_price')
                            ->numeric()
                            ->readOnly()
                            ->dehydrated()
                            ->afterStateHydrated(function ($state, callable $set, callable $get) {
                                self::calculateItemTotal($set, $get);
                            }),
                    ]),
                ])
                ->afterStateUpdated(function (callable $set, callable $get) {
                    self::calculateTotals($set, $get);
                }),

            Grid::make(4)->schema([
                TextInput::make('subtotal')
                    ->numeric()
                    ->readOnly(),

                TextInput::make('discount_total')
                    ->numeric()
                    ->readOnly(),

                TextInput::make('tax_total')
                    ->numeric()
                    ->readOnly(),

                TextInput::make('total')
                    ->numeric()
                    ->readOnly(),
            ]),
        ]);
    }

    protected static function calculateItemTotal(callable $set, callable $get): void
    {
        $qty = (float) ($get('quantity') ?? 0);
        $price = (float) ($get('unit_price') ?? 0);
        $fixed = (float) ($get('discount_value') ?? 0);
        $percent = (float) ($get('discount_percent') ?? 0);

        $lineTotal = $qty * $price;

        $discount = $fixed > 0
            ? $fixed
            : ($lineTotal * ($percent / 100));

        $discount = min($discount, $lineTotal);

        $set('total_price', $lineTotal - $discount);
    }

    protected static function calculateTotals(callable $set, callable $get): void
    {
        $items = $get('../../items') ?? [];

        $subtotal = collect($items)->sum(fn ($item) =>
            ($item['quantity'] ?? 0) * ($item['unit_price'] ?? 0)
        );

        $discountTotal = collect($items)->sum(function ($item) {
            $qty = (float) ($item['quantity'] ?? 0);
            $price = (float) ($item['unit_price'] ?? 0);
            $fixed = (float) ($item['discount_value'] ?? 0);
            $percent = (float) ($item['discount_percent'] ?? 0);

            $lineTotal = $qty * $price;

            return $fixed > 0
                ? $fixed
                : ($lineTotal * ($percent / 100));
        });

        $totalBeforeTax = max($subtotal - $discountTotal, 0);

        $taxValue = (float) ($get('../../tax_value') ?? 0);
        $taxPercent = (float) ($get('../../tax_percent') ?? 0);

        $taxTotal = $taxValue > 0
            ? $taxValue
            : ($totalBeforeTax * ($taxPercent / 100));

        $set('../../subtotal', round($subtotal, 2));
        $set('../../discount_total', round($discountTotal, 2));
        $set('../../tax_total', round($taxTotal, 2));
        $set('../../total', round($totalBeforeTax + $taxTotal, 2));
    }
}
