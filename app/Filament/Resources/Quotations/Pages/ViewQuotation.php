<?php

namespace App\Filament\Resources\Quotations\Pages;

use App\Filament\Resources\Quotations\QuotationResource;
use Modules\Quotations\Services\QuotationPdfService;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewQuotation extends ViewRecord
{
    protected static string $resource = QuotationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('print_arabic')
                ->label('Print (Arabic)')
                ->icon('heroicon-o-document-text')
                ->color('gray')
                ->action(function ($record) {
                    $pdfService = new QuotationPdfService();
                    return $pdfService->generatePdf($record, 'ar');
                }),

            Action::make('print_english')
                ->label('Print (English)')
                ->icon('heroicon-o-document-text')
                ->color('primary')
                ->action(function ($record) {
                    $pdfService = new QuotationPdfService();
                    return $pdfService->generatePdf($record, 'en');
                }),
            EditAction::make(),
        ];
    }
}
