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
                    ->label(__('quotations.fields.number'))
                    ->sortable()
                    ->searchable(),

                TextColumn::make('issue_date')
                    ->label(__('quotations.fields.issue_date'))
                    ->sortable()
                    ->date(),

                TextColumn::make('valid_until')
                    ->label(__('quotations.fields.valid_until'))
                    ->sortable()
                    ->date(),

                TextColumn::make('client.company_name')
                    ->label(__('quotations.fields.client'))
                    ->sortable()
                    ->searchable(),

                BadgeColumn::make('computed_status')
                    ->label(__('quotations.fields.computed_status'))
                    ->colors([
                        'draft' => 'secondary',
                        'sent' => 'primary',
                        'approved' => 'success',
                        'rejected' => 'danger',
                        'expired' => 'warning',
                    ])
                    ->formatStateUsing(fn (string $state) => __('quotations.computed_statuses.' . strtolower($state))),

                TextColumn::make('total')
                    ->label(__('quotations.fields.total'))
                    ->sortable()
                    ->money(fn ($record) => $record->currency),
            ])
            ->filters([
                SelectFilter::make('computed_status')
                    ->label(__('quotations.filters.status'))
                    ->options([
                        'expired' => __('quotations.filters.status_options.expired'),
                        'draft' => __('quotations.filters.status_options.draft'),
                        'sent' => __('quotations.filters.status_options.sent'),
                        'approved' => __('quotations.filters.status_options.approved'),
                        'rejected' => __('quotations.filters.status_options.rejected'),
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
                    ->label(__('quotations.actions.print'))
                    ->icon('heroicon-o-document-text')
                    ->color('primary')
                    ->modalHeading(__('quotations.prompts.select_print_language'))
                    ->form([
                        Radio::make('lang')
                            ->label(__('quotations.prompts.print_language'))
                            ->options([
                                'ar' => __('quotations.languages.ar'),
                                'en' => __('quotations.languages.en'),
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
