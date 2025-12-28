<?php

namespace App\Filament\Resources\Quotations\Schemas;

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

class QuotationForm
{
    public static function configure(Schema $schema): Schema
    {
        $defaultTax = Setting::first()?->tax ?? 0;

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
                        'Sent' => 'Sent',
                        'Under Review' => 'Under Review',
                        'Accepted' => 'Accepted',
                        'Rejected' => 'Rejected',
                    ])
                    ->default('Draft')
                    ->required(),

                Select::make('currency')
                    ->label('Currency')
                    ->options(function () {
                        $currencies = json_decode(
                            File::get(
                                base_path('vendor/umpirsky/currency-list/data/en/currency.json')
                            ),
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

                        $newSubtotal = 0;
                        $newDiscountTotal = 0;

                        foreach ($items as $index => $item) {
                            $unitPrice = (float) ($item['unit_price'] ?? 0);
                            $qty = (float) ($item['quantity'] ?? 1);

                            if (!empty($item['product_id'])) {
                                $product = Product::find($item['product_id']);

                                if ($product) {
                                    $productCurrency = $product->currency ?? 'USD';
                                    $productPrice = $product->price;

                                    $set("items.{$index}.original_currency", $productCurrency);
                                    $set("items.{$index}.original_price", $productPrice);

                                    if ($state === $productCurrency) {
                                        $set("items.{$index}.conversion_rate", 1);
                                        $unitPrice = $productPrice;
                                        $set("items.{$index}.unit_price", $unitPrice);
                                    } else {
                                        $currencyService = app(CurrencyService::class);
                                        $rate = $currencyService->getRate($productCurrency, $state);

                                        if ($rate) {
                                            $convertedPrice = $productPrice * $rate;
                                            $set("items.{$index}.conversion_rate", $rate);
                                            $unitPrice = round($convertedPrice, 2);
                                            $set("items.{$index}.unit_price", $unitPrice);
                                        } else {
                                            $set("items.{$index}.conversion_rate", 1);
                                            $unitPrice = $productPrice;
                                            $set("items.{$index}.unit_price", $unitPrice);
                                        }
                                    }
                                }
                            }

                            $fixed = (float) ($item['discount_value'] ?? 0);
                            $percent = (float) ($item['discount_percent'] ?? 0);

                            $lineTotal = $qty * $unitPrice;

                            $discountAmount = $fixed > 0 ? $fixed : ($lineTotal * ($percent / 100));
                            $discountAmount = min($discountAmount, $lineTotal);

                            $finalLinePrice = $lineTotal - $discountAmount;

                            $set("items.{$index}.total_price", $finalLinePrice);

                            $newSubtotal += $lineTotal;
                            $newDiscountTotal += $discountAmount;
                        }

                        $totalBeforeTax = max($newSubtotal - $newDiscountTotal, 0);

                        $taxValue = (float) $get('tax_value');
                        $taxPercent = (float) $get('tax_percent');

                        $taxTotal = $taxValue > 0
                            ? $taxValue
                            : ($totalBeforeTax * ($taxPercent / 100));

                        $set('subtotal', round($newSubtotal, 2));
                        $set('discount_total', round($newDiscountTotal, 2));
                        $set('tax_total', round($taxTotal, 2));
                        $set('total', round($totalBeforeTax + $taxTotal, 2));
                    }),
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
                ->live(onBlur: true)
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
                                $set('original_currency', null);
                                $set('original_price', null);
                                $set('conversion_rate', 1);

                                self::calculateTotals($set, $get);
                                return;
                            }

                            $product = Product::find($state);

                            if (! $product) {
                                return;
                            }

                            $quotationCurrency = $get('../../currency');
                            $productCurrency = $product->currency ?? 'USD';
                            $productPrice = $product->price;

                            $set('original_currency', $productCurrency);
                            $set('original_price', $productPrice);

                            if ($quotationCurrency === $productCurrency) {
                                $set('conversion_rate', 1);
                                $set('unit_price', $productPrice);
                            } else {
                                $currencyService = app(CurrencyService::class);
                                $rate = $currencyService->getRate($productCurrency, $quotationCurrency);

                                if ($rate) {
                                    $convertedPrice = $productPrice * $rate;
                                    $set('conversion_rate', $rate);
                                    $set('unit_price', round($convertedPrice, 2));
                                } else {
                                    $set('conversion_rate', 1);
                                    $set('unit_price', $productPrice);
                                }
                            }

                            $set('description', $product->description);

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
                            ->dehydrated()
                            ->visible(fn (callable $get) => filled($get('original_price'))),

                        TextInput::make('original_currency')
                            ->label('Original Currency')
                            ->readOnly()
                            ->dehydrated()
                            ->visible(fn (callable $get) => filled($get('original_currency'))),
                    ]),

                    Grid::make(5)->schema([
                        TextInput::make('unit_price')
                            ->label('Unit Price (Converted)')
                            ->minValue(1)
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
                            ->minValue(0)
                            ->live(debounce: 300)
                            ->disabled(fn (callable $get) => (float) $get('discount_percent') > 0)
                            ->extraInputAttributes([
                                'onfocus' => "if(this.value == '0') this.value='';",
                                'onblur' => "if(this.value == '') this.value='0';",
                            ])
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                $set('discount_value', $state !== null && $state !== '' ? (float)$state : 0);
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
                                $set('discount_percent', $state !== null && $state !== '' ? (float)$state : 0);
                                self::calculateItemTotal($set, $get);
                                self::calculateTotals($set, $get);
                            }),

                        TextInput::make('total_price')
                            ->label('Total')
                            ->numeric()
                            ->readOnly()
                            ->dehydrated()
                            ->suffix(fn (callable $get) => $get('../../currency'))
                            ->afterStateHydrated(function ($state, callable $set, callable $get) {
                                self::calculateItemTotal($set, $get);
                            }),
                    ]),
                ])
                ->afterStateUpdated(function (callable $set, callable $get) {
                    self::calculateTotals($set, $get);
                }),

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
