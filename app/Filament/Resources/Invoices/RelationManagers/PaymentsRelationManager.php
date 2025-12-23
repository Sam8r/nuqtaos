<?php

namespace App\Filament\Resources\Invoices\RelationManagers;

use App\Filament\Custom\TextEntry;
use Filament\Actions\CreateAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PaymentsRelationManager extends RelationManager
{
    protected static string $relationship = 'payments';

    protected static ?string $title = 'Payments';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                DatePicker::make('payment_date')
                    ->label('Payment Date')
                    ->default(now())
                    ->required(),

                TextInput::make('amount')
                    ->label('Amount')
                    ->numeric()
                    ->required(),

                Select::make('payment_method')
                    ->label('Payment Method')
                    ->options([
                        'Cash' => 'Cash',
                        'Bank Transfer' => 'Bank Transfer',
                        'Credit Card' => 'Credit Card',
                        'Cheque' => 'Cheque',
                        'Online Payment' => 'Online Payment',
                    ])
                    ->required(),

                Textarea::make('notes')
                    ->label('Notes')
                    ->rows(3)
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('payment_date')
            ->columns([
                TextColumn::make('payment_date')
                    ->label('Date')
                    ->sortable(),

                TextColumn::make('amount')
                    ->label('Amount'),

                TextColumn::make('payment_method')
                    ->label('Method')
                    ->badge(),

                TextColumn::make('notes')
                    ->label('Notes')
                    ->limit(50),
            ])
            ->filters([
                SelectFilter::make('payment_method')
                    ->label('Payment Method')
                    ->options([
                        'Cash' => 'Cash',
                        'Bank Transfer' => 'Bank Transfer',
                        'Credit Card' => 'Credit Card',
                        'Cheque' => 'Cheque',
                        'Online Payment' => 'Online Payment',
                    ]),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->actions([
                ViewAction::make()
                    ->label('View Payment')
                    ->modalWidth('xl')
                    ->form([
                        TextEntry::make('payment_date')->label('Payment Date'),
                        TextEntry::make('amount')->label('Amount'),
                        TextEntry::make('payment_method')->label('Method'),
                        TextEntry::make('notes')->label('Notes'),
                    ]),
            ]);
    }
}
