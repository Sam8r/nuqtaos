<?php

namespace App\Filament\Resources\Invoices\Tables;

use App\Filament\Resources\Invoices\InvoiceResource;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Radio;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Modules\Invoices\Services\InvoicesPdfService;
use Modules\Quotations\Services\QuotationPdfService;

class InvoicesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('number')
                    ->label('Invoice #')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('issue_date')
                    ->sortable()
                    ->date(),

                TextColumn::make('due_date')
                    ->sortable()
                    ->date(),

                TextColumn::make('client.company_name')
                    ->label('Client')
                    ->sortable()
                    ->searchable(),

                BadgeColumn::make('status')
                    ->colors([
                        'Draft' => 'secondary',
                        'Pending' => 'warning',
                        'Partially Paid' => 'info',
                        'Paid' => 'success',
                        'Overdue' => 'danger',
                    ]),

                TextColumn::make('total')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('computed_status')
                    ->label('Status')
                    ->options([
                        'Draft' => 'Draft',
                        'Pending' => 'Pending',
                        'Partially Paid' => 'Partially Paid',
                        'Paid' => 'Paid',
                        'Overdue' => 'Overdue',
                    ])
                    ->query(function (Builder $query, array $data) {
                        if (!isset($data['value'])) {
                            return;
                        }

                        $query->where(function ($q) use ($data) {
                            $value = $data['value'];
                            $q->when($value === 'Draft', fn ($q) => $q->where('total', '<=', 0))
                                ->when($value === 'Pending', fn ($q) => $q->where('total', '>', 0)->where('paid_amount', 0)->where(function ($q2) {
                                    $q2->where('due_date', '>=', now())->orWhereNull('due_date');
                                }))
                                ->when($value === 'Partially Paid', fn ($q) => $q->where('paid_amount', '>', 0)->where('paid_amount', '<', \DB::raw('total'))->where(function ($q2) {
                                    $q2->where('due_date', '>=', now())->orWhereNull('due_date');
                                }))
                                ->when($value === 'Paid', fn ($q) => $q->whereColumn('paid_amount', '>=', 'total'))
                                ->when($value === 'Overdue', fn ($q) => $q->whereColumn('paid_amount', '<', 'total')->whereDate('due_date', '<', now()));
                        });
                    }),
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
                        $pdfService = new InvoicesPdfService();
                        return $pdfService->generatePdf($record, $data['lang']);
                    }),
            ])
            ->toolbarActions([

            ]);
    }
}
