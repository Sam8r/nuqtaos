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
                Section::make(__('invoices.sections.details'))
                    ->schema([
                        TextEntry::make('number')
                            ->label(__('invoices.fields.number')),

                        TextEntry::make('issue_date')
                            ->label(__('invoices.fields.issue_date'))
                            ->date(),

                        TextEntry::make('due_date')
                            ->label(__('invoices.fields.due_date'))
                            ->date(),

                        TextEntry::make('computed_status')
                            ->label(__('invoices.fields.computed_status')),

                        TextEntry::make('client.company_name')
                            ->label(__('invoices.fields.client')),

                        TextEntry::make('payment_terms')
                            ->label(__('invoices.fields.payment_terms'))
                            ->markdown(),
                    ])
                    ->columns(2),

                Section::make(__('invoices.sections.financial_summary'))
                    ->schema([
                        TextEntry::make('subtotal')
                            ->label(__('invoices.fields.subtotal')),

                        TextEntry::make('discount_value')
                            ->label(__('invoices.fields.discount_value')),

                        TextEntry::make('discount_percent')
                            ->label(__('invoices.fields.discount_percent')),

                        TextEntry::make('tax_value')
                            ->label(__('invoices.fields.tax_value')),

                        TextEntry::make('tax_percent')
                            ->label(__('invoices.fields.tax_percent')),

                        TextEntry::make('late_payment_penalty_percent')
                            ->label(__('invoices.fields.late_payment_penalty_percent')),

                        TextEntry::make('discount_total')
                            ->label(__('invoices.fields.discount_total')),

                        TextEntry::make('tax_total')
                            ->label(__('invoices.fields.tax_total')),

                        TextEntry::make('total')
                            ->label(__('invoices.fields.total')),

                        TextEntry::make('total_paid')
                            ->label(__('invoices.fields.total_paid'))
                            ->state(fn ($record) => $record->payments()->sum('amount'))
                            ->money(fn ($record) => $record->currency),

                        TextEntry::make('outstanding_balance')
                            ->label(__('invoices.fields.outstanding_balance'))
                            ->state(fn ($record) => $record->total - $record->payments()->sum('amount'))
                            ->money(fn ($record) => $record->currency),
                    ])
                    ->columns(2),

                Section::make(__('invoices.sections.items'))
                    ->schema([
                        RepeatableEntry::make('items')
                            ->schema([
                                Grid::make(6)
                                    ->schema([
                                        TextEntry::make('product_id')
                                            ->label(__('invoices.fields.product'))
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
                                            ->label(__('invoices.fields.description'))
                                            ->markdown()
                                            ->columnSpan(2),

                                        TextEntry::make('quantity')
                                            ->label(__('invoices.fields.quantity')),

                                        TextEntry::make('unit_price')
                                            ->label(__('invoices.fields.unit_price')),

                                        TextEntry::make('total_price')
                                            ->label(__('invoices.fields.total_price')),

                                        TextEntry::make('discount_value')
                                            ->label(__('invoices.fields.discount_value')),

                                        TextEntry::make('discount_percent')
                                            ->label(__('invoices.fields.discount_percent')),
                                    ]),
                            ])
                            ->label(__('invoices.fields.items'))
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
