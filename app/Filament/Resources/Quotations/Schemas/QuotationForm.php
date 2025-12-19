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
                        ->live()
                        ->afterStateUpdated(function ($state, callable $set, callable $get) {
                            if (! $state) {
                                $set('description', null);
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
                        ->label('Custom Product Name'),

                    Textarea::make('description'),

                    Grid::make(5)->schema([
                        TextInput::make('quantity')
                            ->numeric()
                            ->required()
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

                        TextInput::make('discount_fixed')
                            ->numeric()
                            ->default(0)
                            ->live(debounce: 300)
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                self::calculateItemTotal($set, $get);
                                self::calculateTotals($set, $get);
                            }),

                        TextInput::make('discount_percent')
                            ->numeric()
                            ->default(0)
                            ->maxValue(100)
                            ->live(debounce: 300)
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
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
                    ->readOnly()
                    ->dehydrated(),

                TextInput::make('discount_total')
                    ->numeric()
                    ->readOnly()
                    ->dehydrated(),

                TextInput::make('tax_total')
                    ->numeric()
                    ->readOnly()
                    ->dehydrated(),

                TextInput::make('total')
                    ->numeric()
                    ->readOnly()
                    ->dehydrated(),
            ]),
        ]);
    }

    protected static function calculateItemTotal(callable $set, callable $get): void
    {
        $qty = (float) ($get('quantity') ?? 0);
        $price = (float) ($get('unit_price') ?? 0);
        $fixed = (float) ($get('discount_fixed') ?? 0);
        $percent = (float) ($get('discount_percent') ?? 0);

        $lineTotal = $qty * $price;

        $discount = $fixed + ($lineTotal * ($percent / 100));

        $discount = min($discount, $lineTotal);

        $total = $lineTotal - $discount;

        $set('total_price', $total);
    }

    protected static function calculateTotals(callable $set, callable $get): void
    {
        $items = $get('items') ?? [];

        $subtotal = collect($items)->sum(function ($item) {
            return (float) ($item['quantity'] ?? 0) * (float) ($item['unit_price'] ?? 0);
        });

        $discountTotal = collect($items)->sum(function ($item) {
            $qty = (float) ($item['quantity'] ?? 0);
            $price = (float) ($item['unit_price'] ?? 0);
            $fixed = (float) ($item['discount_fixed'] ?? 0);
            $percent = (float) ($item['discount_percent'] ?? 0);

            $lineTotal = $qty * $price;

            return $fixed + ($lineTotal * ($percent / 100));
        });

        $total = max($subtotal - $discountTotal, 0);

        $set('subtotal', $subtotal);
        $set('discount_total', $discountTotal);
        $set('tax_total', 0);
        $set('total', $total);
    }
}
