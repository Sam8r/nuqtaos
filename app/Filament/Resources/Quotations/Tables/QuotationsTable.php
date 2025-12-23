<?php

namespace App\Filament\Resources\Quotations\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Radio;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Modules\Quotations\Services\QuotationPdfService;

class QuotationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('number')
                    ->label('Quotation #')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('issue_date')
                    ->sortable()
                    ->date(),

                TextColumn::make('valid_until')
                    ->sortable()
                    ->date(),

                TextColumn::make('client.company_name')
                    ->label('Client')
                    ->sortable()
                    ->searchable(),

                BadgeColumn::make('computed_status')
                    ->label('Status')
                    ->colors([
                        'draft' => 'secondary',
                        'sent' => 'primary',
                        'approved' => 'success',
                        'rejected' => 'danger',
                        'warning' => 'expired',
                    ]),

                TextColumn::make('total')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('computed_status')
                    ->label('Status')
                    ->options([
                        'expired' => 'Expired',
                        'draft' => 'Draft',
                        'sent' => 'Sent',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ])
                    ->query(function (Builder $query, array $data) {
                        if ($data['value'] === 'expired') {
                            $query
                                ->whereNotIn('status', ['approved', 'rejected'])
                                ->whereDate('valid_until', '<', now());
                        } elseif ($data['value']) {
                            $query->where('status', $data['value']);
                        }
                    }),
                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                Action::make('print')
                    ->label('Print')
                    ->icon('heroicon-o-document-text')
                    ->color('primary')
                    ->modalHeading('Select Print Language')
                    ->form([
                        Radio::make('lang')
                            ->label('Language')
                            ->options([
                                'ar' => 'Arabic',
                                'en' => 'English',
                            ])
                            ->default('en')
                            ->required(),
                    ])
                    ->action(function (array $data, $record) {
                        $pdfService = new QuotationPdfService();
                        return $pdfService->generatePdf($record, $data['lang']);
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
