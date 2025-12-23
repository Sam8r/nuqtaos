<?php

namespace App\Filament\Resources\Invoices\Schemas;

use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class InvoiceInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Invoice Details')
                    ->schema([
                        TextEntry::make('number')
                            ->label('Invoice #'),

                        TextEntry::make('issue_date')
                            ->label('Issue Date')
                            ->date(),

                        TextEntry::make('due_date')
                            ->label('Due Date')
                            ->date(),

                        TextEntry::make('computed_status')
                            ->label('Status'),

                        TextEntry::make('client.company_name')
                            ->label('Client'),

                        TextEntry::make('payment_terms')
                            ->label('Payment Terms')
                            ->markdown(),
                    ])
                    ->columns(2),

                Section::make('Financial Summary')
                    ->schema([
                        TextEntry::make('subtotal')
                            ->label('Subtotal'),

                        TextEntry::make('discount_value')
                            ->label('Discount Value'),

                        TextEntry::make('discount_percent')
                            ->label('Discount (%)'),

                        TextEntry::make('tax_value')
                            ->label('Tax Value'),

                        TextEntry::make('tax_percent')
                            ->label('Tax (%)'),

                        TextEntry::make('late_payment_penalty_percent')
                            ->label('Late Payment (%)'),

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
                                            ->state(fn ($record) =>
                                            $record->product_id
                                                ? ($record->product->name ?? $record->custom_name)
                                                : $record->custom_name
                                            )
                                            ->url(fn ($record) =>
                                            $record->product_id
                                                ? route('filament.admin.resources.products.view', $record->product_id)
                                                : null
                                            ),

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

                                        TextEntry::make('discount_value')
                                            ->label('Discount Value'),

                                        TextEntry::make('discount_percent')
                                            ->label('Discount (%)'),
                                    ]),
                            ])
                            ->label('Invoice Items')
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
