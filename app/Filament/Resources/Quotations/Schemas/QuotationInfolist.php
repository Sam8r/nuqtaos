<?php

namespace App\Filament\Resources\Quotations\Schemas;

use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class QuotationInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Quotation Details')
                    ->schema([
                        TextEntry::make('number')->label('Quotation #'),
                        TextEntry::make('issue_date')
                            ->label('Issue Date')
                            ->date(),

                        TextEntry::make('valid_until')
                            ->label('Valid Until')
                            ->date(),

                        TextEntry::make('computed_status')->label('Status'),

                        TextEntry::make('client.company_name')
                            ->label('Client'),

                        TextEntry::make('terms_and_conditions')
                            ->label('Terms & Conditions')
                            ->markdown(),
                    ])
                    ->columns(2),

                Section::make('Financial Summary')
                    ->schema([
                        TextEntry::make('subtotal')
                            ->label('Subtotal'),

                        TextEntry::make('discount_total')
                            ->label('Discount Total'),

                        TextEntry::make('tax_total')
                            ->label('Tax Total'),

                        TextEntry::make('total')
                            ->label('Total'),
                    ])
                    ->columns(2),

                Section::make('Items')
                    ->schema([
                        RepeatableEntry::make('items')
                            ->schema([
                                Grid::make(6)
                                    ->schema([
                                        TextEntry::make('product_id')
                                            ->label('Product')
                                            ->state(function ($record) {
                                                return $record->product_id
                                                    ? ($record->product->name ?? '')
                                                    : ($record->custom_name ?? '');
                                            }),

                                        TextEntry::make('description')
                                            ->label('Description')
                                            ->markdown()
                                            ->columnSpan(2),

                                        TextEntry::make('quantity')
                                            ->label('Qty'),

                                        TextEntry::make('unit_price')
                                            ->label('Unit Price'),

                                        TextEntry::make('total_price')
                                            ->label('Total'),
                                    ]),
                            ])
                            ->label('Quotation Items')
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
