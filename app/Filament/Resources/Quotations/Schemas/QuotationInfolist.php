<?php

namespace App\Filament\Resources\Quotations\Schemas;

use Filament\Facades\Filament;
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
                Section::make(__('quotations.sections.details'))
                    ->schema([
                        TextEntry::make('number')->label(__('quotations.fields.number')),
                        TextEntry::make('issue_date')
                            ->label(__('quotations.fields.issue_date'))
                            ->date(),

                        TextEntry::make('valid_until')
                            ->label(__('quotations.fields.valid_until'))
                            ->date(),

                        TextEntry::make('computed_status')->label(__('quotations.fields.computed_status')),

                        TextEntry::make('client.company_name')
                            ->label(__('quotations.fields.client')),

                        TextEntry::make('terms_and_conditions')
                            ->label(__('quotations.fields.terms_and_conditions'))
                            ->markdown(),
                    ])
                    ->columns(2),

                Section::make(__('quotations.sections.financial_summary'))
                    ->schema([
                        TextEntry::make('subtotal')
                            ->label(__('quotations.fields.subtotal')),

                        TextEntry::make('discount_value')
                            ->label(__('quotations.fields.discount_value')),

                        TextEntry::make('discount_percent')
                            ->label(__('quotations.fields.discount_percent')),

                        TextEntry::make('tax_value')
                            ->label(__('quotations.fields.tax_value')),

                        TextEntry::make('tax_percent')
                            ->label(__('quotations.fields.tax_percent')),

                        TextEntry::make('discount_total')
                            ->label(__('quotations.fields.discount_total')),

                        TextEntry::make('tax_total')
                            ->label(__('quotations.fields.tax_total')),

                        TextEntry::make('total')
                            ->label(__('quotations.fields.total')),
                    ])
                    ->columns(2),

                Section::make(__('quotations.sections.items'))
                    ->schema([
                        RepeatableEntry::make('items')
                            ->schema([
                                Grid::make(6)
                                    ->schema([
                                        TextEntry::make('product_id')
                                            ->label(__('quotations.fields.product'))
                                            ->state(fn ($record) => $record->product_id ? ($record->product->name ?? $record->custom_name) : $record->custom_name)
                                            ->state(fn ($record) => $record->product_id ? ($record->product->name ?? $record->custom_name) : $record->custom_name)
                                            ->url(fn ($record) => $record->product_id
                                                ? route('filament.admin.resources.products.view', $record->product_id)
                                                : null
                                            ),

                                        TextEntry::make('description')
                                            ->label(__('quotations.fields.description'))
                                            ->markdown()
                                            ->columnSpan(2),

                                        TextEntry::make('quantity')
                                            ->label(__('quotations.fields.quantity')),

                                        TextEntry::make('unit_price')
                                            ->label(__('quotations.fields.unit_price')),

                                        TextEntry::make('total_price')
                                            ->label(__('quotations.fields.total_price')),

                                        TextEntry::make('discount_value')
                                            ->label(__('quotations.fields.discount_value')),

                                        TextEntry::make('discount_percent')
                                            ->label(__('quotations.fields.discount_percent')),
                                    ]),
                            ])
                            ->label(__('quotations.fields.items'))
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
