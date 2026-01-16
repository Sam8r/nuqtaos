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

        $locale = strtolower(str_replace('_', '-', app()->getLocale() ?? 'en'));
        $primaryLocale = explode('-', $locale)[0];

        $currencyPath = base_path("vendor/umpirsky/currency-list/data/{$primaryLocale}/currency.json");

        if (! File::exists($currencyPath)) {
            $currencyPath = base_path('vendor/umpirsky/currency-list/data/en/currency.json');
        }

        $currencies = json_decode(File::get($currencyPath), true);

        return $schema->components([
            Grid::make(3)->schema([
                TextInput::make('number')
                    ->label(__('invoices.fields.number'))
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->default(fn () => 'INV-' . now()->format('Ymd') . '-' . Str::upper(Str::random(4))),

                DatePicker::make('issue_date')
                    ->label(__('invoices.fields.issue_date'))
                    ->required()
                    ->default(now())
                    ->live(),

                DatePicker::make('due_date')
                    ->label(__('invoices.fields.due_date'))
                    ->required()
                    ->minDate(fn (callable $get) => $get('issue_date')),

                TextInput::make('tax_value')
                    ->label(__('invoices.fields.tax_value'))
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
                    ->label(__('invoices.fields.tax_percent'))
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
                    ->label(__('invoices.fields.status'))
                    ->options([
                        'Draft' => __('invoices.statuses.Draft'),
                        'Pending' => __('invoices.statuses.Pending'),
                        'Partially Paid' => __('invoices.statuses.Partially Paid'),
                        'Paid' => __('invoices.statuses.Paid'),
                        'Overdue' => __('invoices.statuses.Overdue'),
                    ])
                    ->default('Draft')
                    ->required(),

                Select::make('currency')
                    ->label(__('invoices.fields.currency'))
                    ->options($currencies)
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
                ->label(__('invoices.fields.client'))
                ->relationship('client', 'company_name')
                ->searchable()
                ->preload()
                ->required(),

            Textarea::make('payment_terms')
                ->label(__('invoices.fields.payment_terms'))
                ->columnSpanFull(),

            Repeater::make('items')
                ->label(__('invoices.fields.items'))
                ->relationship()
                ->columnSpanFull()
                ->live(onBlur: true)
                ->schema([
                    Select::make('product_id')
                        ->label(__('invoices.fields.product'))
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
                        ->label(__('invoices.fields.custom_name'))
                        ->nullable()
                        ->disabled(fn (callable $get) => (bool) $get('product_id')),

                    Textarea::make('description')
                        ->label(__('invoices.fields.description')),

                    Grid::make(2)->schema([
                        TextInput::make('original_price')
                            ->label(__('invoices.fields.original_price'))
                            ->numeric()
                            ->readOnly()
                            ->visible(fn (callable $get) => filled($get('original_price'))),

                        TextInput::make('original_currency')
                            ->label(__('invoices.fields.original_currency'))
                            ->readOnly()
                            ->visible(fn (callable $get) => filled($get('original_currency'))),
                    ]),

                    Grid::make(5)->schema([
                        TextInput::make('unit_price')
                            ->label(__('invoices.fields.unit_price_converted'))
                            ->numeric()
                            ->required()
                            ->live(debounce: 300)
                            ->suffix(fn (callable $get) => $get('../../currency'))
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                self::calculateItemTotal($set, $get);
                                self::calculateTotals($set, $get);
                            }),

                        TextInput::make('quantity')
                            ->label(__('invoices.fields.quantity'))
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
                            ->label(__('invoices.fields.discount_value'))
                            ->numeric()
                            ->default(0)
                            ->live(debounce: 300)
                            ->disabled(fn (callable $get) => (float) $get('discount_percent') > 0)
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                self::calculateItemTotal($set, $get);
                                self::calculateTotals($set, $get);
                            }),

                        TextInput::make('discount_percent')
                            ->label(__('invoices.fields.discount_percent'))
                            ->numeric()
                            ->default(0)
                            ->live(debounce: 300)
                            ->disabled(fn (callable $get) => (float) $get('discount_value') > 0)
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                self::calculateItemTotal($set, $get);
                                self::calculateTotals($set, $get);
                            }),

                        TextInput::make('total_price')
                            ->label(__('invoices.fields.total_price'))
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
                    ->label(__('invoices.fields.subtotal'))
                    ->content(fn (callable $get) => number_format((float)$get('subtotal'), 2) . ' ' . $get('currency')),

                Placeholder::make('display_discount_total')
                    ->label(__('invoices.fields.discount_total'))
                    ->content(fn (callable $get) => number_format((float)$get('discount_total'), 2) . ' ' . $get('currency')),

                Placeholder::make('display_tax_total')
                    ->label(__('invoices.fields.tax_total'))
                    ->content(fn (callable $get) => number_format((float)$get('tax_total'), 2) . ' ' . $get('currency')),

                Placeholder::make('display_total')
                    ->label(__('invoices.fields.grand_total'))
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
