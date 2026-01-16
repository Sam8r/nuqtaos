<?php

namespace App\Filament\Resources\Invoices\Pages;

use App\Filament\Resources\Invoices\InvoiceResource;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Modules\Invoices\Services\InvoicesPdfService;

class ViewInvoice extends ViewRecord
{
    protected static string $resource = InvoiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('print_arabic')
                ->label(__('invoices.actions.print_arabic'))
                ->icon('heroicon-o-document-text')
                ->color('gray')
                ->action(function ($record) {
                    $pdfService = new InvoicesPdfService();
                    return $pdfService->generatePdf($record, 'ar');
                }),

            Action::make('print_english')
                ->label(__('invoices.actions.print_english'))
                ->icon('heroicon-o-document-text')
                ->color('primary')
                ->action(function ($record) {
                    $pdfService = new InvoicesPdfService();
                    return $pdfService->generatePdf($record, 'en');
                }),

            Action::make('activities')->url(fn ($record) => InvoiceResource::getUrl('activities', ['record' => $record])),

            EditAction::make(),
        ];
    }
}
