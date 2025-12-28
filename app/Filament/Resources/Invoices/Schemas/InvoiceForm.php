<?php

namespace App\Filament\Resources\Invoices\Schemas;

use App\Services\CurrencyService;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Modules\Products\Models\Product;
use Modules\Settings\Models\Setting;

class InvoiceForm
{
    public static function configure(Schema $schema): Schema
    {
        $defaultTax = Setting::first()?->tax ?? 0;

        return $schema->components([
            Grid::make(3)->schema([
                TextInput::make('number')
                    ->label('Invoice #')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->default(fn () => 'INV-' . now()->format('Ymd') . '-' . Str::upper(Str::random(4))),

                DatePicker::make('issue_date')
                    ->required()
                    ->default(now())
                    ->live(),

                DatePicker::make('due_date')
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
                    ->default($defaultTax)
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
                        'Pending' => 'Pending',
                        'Partially Paid' => 'Partially Paid',
                        'Paid' => 'Paid',
                        'Overdue' => 'Overdue',
                    ])
                    ->default('Draft')
                    ->required(),

                Select::make('currency')
                    ->label('Currency')
                    ->options(function () {
                        $currencies = json_decode(
                            File::get(base_path('vendor/umpirsky/currency-list/data/en/currency.json')),
                            true
                        );
                        return $currencies;
                    })
                    ->searchable()
                    ->required()
                    ->default(fn () => Setting::first()?->currency)
                    ->live()
                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                        $items = $get('items') ?? [];
                        foreach ($items as $index => $item) {
                            if (!empty($item['product_id'])) {
                                $product = Product::find($item['product_id']);
                                if ($product) {
                                    $productCurrency = $product->currency ?? 'USD';
                                    $productPrice = $product->price;

                                    $set("items.{$index}.original_currency", $productCurrency);
                                    $set("items.{$index}.original_price", $productPrice);

                                    if ($state === $productCurrency) {
                                        $set("items.{$index}.conversion_rate", 1);
                                        $set("items.{$index}.unit_price", $productPrice);
                                    } else {
                                        $rate = app(CurrencyService::class)->getRate($productCurrency, $state);
                                        $set("items.{$index}.conversion_rate", $rate ?? 1);
                                        $set("items.{$index}.unit_price", round($productPrice * ($rate ?? 1), 2));
                                    }
                                }
                            }
                            self::calculateItemTotalInsideRepeater($index, $set, $get);
                        }
                        self::calculateTotals($set, $get);
                    }),
            ]),

            Select::make('client_id')
                ->relationship('client', 'company_name')
                ->searchable()
                ->preload()
                ->required(),

            Textarea::make('payment_terms')
                ->columnSpanFull(),

            Repeater::make('items')
                ->relationship()
                ->columnSpanFull()
                ->live(onBlur: true)
                ->schema([
                    Select::make('product_id')
                        ->relationship('product', 'name')
                        ->searchable()
                        ->preload()
                        ->live()
                        ->disabled(fn (callable $get) => (bool) $get('custom_name'))
                        ->afterStateUpdated(function ($state, callable $set, callable $get) {
                            if (! $state) {
                                $set('description', null);
                                $set('unit_price', 0);
                                $set('original_currency', null);
                                $set('original_price', null);
                                self::calculateTotals($set, $get);
                                return;
                            }

                            $product = Product::find($state);
                            if ($product) {
                                $quotationCurrency = $get('../../currency');
                                $productCurrency = $product->currency ?? 'USD';
                                $productPrice = $product->price;

                                $set('original_currency', $productCurrency);
                                $set('original_price', $productPrice);

                                if ($quotationCurrency === $productCurrency) {
                                    $set('conversion_rate', 1);
                                    $set('unit_price', $productPrice);
                                } else {
                                    $rate = app(CurrencyService::class)->getRate($productCurrency, $quotationCurrency);
                                    $set('conversion_rate', $rate ?? 1);
                                    $set('unit_price', round($productPrice * ($rate ?? 1), 2));
                                }
                                $set('description', $product->description);
                                $set('quantity', 1);
                            }
                            self::calculateItemTotal($set, $get);
                            self::calculateTotals($set, $get);
                        }),

                    TextInput::make('custom_name')
                        ->label('Custom Product Name')
                        ->nullable()
                        ->disabled(fn (callable $get) => (bool) $get('product_id')),

                    Textarea::make('description'),

                    Grid::make(2)->schema([
                        TextInput::make('original_price')
                            ->label('Original Price')
                            ->numeric()
                            ->readOnly()
                            ->visible(fn (callable $get) => filled($get('original_price'))),

                        TextInput::make('original_currency')
                            ->label('Original Currency')
                            ->readOnly()
                            ->visible(fn (callable $get) => filled($get('original_currency'))),
                    ]),

                    Grid::make(5)->schema([
                        TextInput::make('unit_price')
                            ->label('Unit Price (Converted)')
                            ->numeric()
                            ->required()
                            ->live(debounce: 300)
                            ->suffix(fn (callable $get) => $get('../../currency'))
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                self::calculateItemTotal($set, $get);
                                self::calculateTotals($set, $get);
                            }),

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

                        TextInput::make('discount_value')
                            ->label('Discount Value')
                            ->numeric()
                            ->default(0)
                            ->live(debounce: 300)
                            ->disabled(fn (callable $get) => (float) $get('discount_percent') > 0)
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                self::calculateItemTotal($set, $get);
                                self::calculateTotals($set, $get);
                            }),

                        TextInput::make('discount_percent')
                            ->label('Discount (%)')
                            ->numeric()
                            ->default(0)
                            ->live(debounce: 300)
                            ->disabled(fn (callable $get) => (float) $get('discount_value') > 0)
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                self::calculateItemTotal($set, $get);
                                self::calculateTotals($set, $get);
                            }),

                        TextInput::make('total_price')
                            ->label('Total')
                            ->numeric()
                            ->readOnly()
                            ->dehydrated()
                            ->suffix(fn (callable $get) => $get('../../currency')),
                    ]),
                ])
                ->afterStateUpdated(fn (callable $set, callable $get) => self::calculateTotals($set, $get)),

            Hidden::make('subtotal')->default(0),
            Hidden::make('discount_total')->default(0),
            Hidden::make('tax_total')->default(0),
            Hidden::make('total')->default(0),

            Grid::make(4)->schema([
                Placeholder::make('display_subtotal')
                    ->label('Subtotal')
                    ->content(fn (callable $get) => number_format((float)$get('subtotal'), 2) . ' ' . $get('currency')),

                Placeholder::make('display_discount_total')
                    ->label('Discount Total')
                    ->content(fn (callable $get) => number_format((float)$get('discount_total'), 2) . ' ' . $get('currency')),

                Placeholder::make('display_tax_total')
                    ->label('Tax Total')
                    ->content(fn (callable $get) => number_format((float)$get('tax_total'), 2) . ' ' . $get('currency')),

                Placeholder::make('display_total')
                    ->label('Grand Total')
                    ->content(fn (callable $get) => number_format((float)$get('total'), 2) . ' ' . $get('currency'))
                    ->extraAttributes(['class' => 'font-bold text-xl text-primary-600']),
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
        $discount = $fixed > 0 ? $fixed : ($lineTotal * ($percent / 100));
        $discount = min($discount, $lineTotal);

        $set('total_price', round($lineTotal - $discount, 2));
    }

    protected static function calculateItemTotalInsideRepeater($index, callable $set, callable $get): void
    {
        $item = $get("items.{$index}");
        $qty = (float) ($item['quantity'] ?? 0);
        $price = (float) ($item['unit_price'] ?? 0);
        $fixed = (float) ($item['discount_value'] ?? 0);
        $percent = (float) ($item['discount_percent'] ?? 0);

        $lineTotal = $qty * $price;
        $discount = $fixed > 0 ? $fixed : ($lineTotal * ($percent / 100));
        $set("items.{$index}.total_price", round($lineTotal - min($discount, $lineTotal), 2));
    }

    protected static function calculateTotals(callable $set, callable $get): void
    {
        $items = $get('../../items') ?? $get('items') ?? [];

        $subtotal = collect($items)->sum(fn ($item) => (float)($item['quantity'] ?? 0) * (float)($item['unit_price'] ?? 0));

        $discountTotal = collect($items)->sum(function ($item) {
            $line = (float)($item['quantity'] ?? 0) * (float)($item['unit_price'] ?? 0);
            $fixed = (float)($item['discount_value'] ?? 0);
            $percent = (float)($item['discount_percent'] ?? 0);
            return $fixed > 0 ? $fixed : ($line * ($percent / 100));
        });

        $totalBeforeTax = max($subtotal - $discountTotal, 0);

        $taxValue = (float) ($get('../../tax_value') ?? $get('tax_value') ?? 0);
        $taxPercent = (float) ($get('../../tax_percent') ?? $get('tax_percent') ?? 0);
        $taxTotal = $taxValue > 0 ? $taxValue : ($totalBeforeTax * ($taxPercent / 100));

        $prefix = $get('../../items') !== null ? '../../' : '';

        $set($prefix . 'subtotal', round($subtotal, 2));
        $set($prefix . 'discount_total', round($discountTotal, 2));
        $set($prefix . 'tax_total', round($taxTotal, 2));
        $set($prefix . 'total', round($totalBeforeTax + $taxTotal, 2));
    }
}
