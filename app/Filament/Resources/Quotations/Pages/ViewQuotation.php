<?php

namespace App\Filament\Resources\Quotations\Pages;

use App\Filament\Resources\Quotations\QuotationResource;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Illuminate\Support\Str;
use Modules\Invoices\Models\Invoice;
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

            Action::make('convert_to_invoice')
                ->label('Convert to Invoice')
                ->button()
                ->color('success')
                ->icon('heroicon-o-receipt-percent')
                ->modalHeading('Convert Quotation to Invoice')
                ->modalWidth('lg')
                ->form([
                    DatePicker::make('issue_date')
                        ->required()
                        ->default(now())
                        ->live(),

                    DatePicker::make('due_date')
                        ->required()
                        ->minDate(fn ($get) => $get('issue_date')),

                    TextInput::make('late_payment_penalty_percent')
                        ->label('Late Payment (%)')
                        ->numeric()
                        ->default(0)
                        ->minValue(0)
                        ->maxValue(100)
                        ->extraInputAttributes([
                            'onfocus' => "if(this.value == '0') this.value='';",
                            'onblur' => "if(this.value == '') this.value='0';",
                        ])
                        ->afterStateUpdated(function ($state, callable $set, callable $get) {
                            $set($state !== null && $state !== '' ? (float)$state : 0, null);
                        }),

                    Select::make('status')
                        ->label('Status')
                        ->options([
                            'Draft' => 'Draft',
                            'Pending' => 'Pending',
                            'Partially Paid' => 'Partially Paid',
                            'Paid' => 'Paid',
                            'Overdue' => 'Overdue',
                        ])
                        ->default('Draft')
                        ->required(),
                ])
                ->action(function ($record, array $data) {
                    $invoice = Invoice::create([
                        'number' => 'INV-' . now()->format('Ymd') . '-' . strtoupper(Str::random(4)),
                        'issue_date' => $data['issue_date'],
                        'due_date' => $data['due_date'],
                        'client_id' => $record->client_id,
                        'tax_value' => $record->tax_value,
                        'tax_percent' => $record->tax_percent,
                        'status' => $data['status'],
                        'subtotal' => $record->subtotal,
                        'discount_total' => $record->discount_total,
                        'tax_total' => $record->tax_total,
                        'total' => $record->total,
                    ]);

                    foreach ($record->items as $item) {
                        $invoice->items()->create([
                            'product_id' => $item->product_id,
                            'custom_name' => $item->custom_name,
                            'description' => $item->description,
                            'quantity' => $item->quantity,
                            'unit_price' => $item->unit_price,
                            'discount_value' => $item->discount_value,
                            'discount_percent' => $item->discount_percent,
                            'total_price' => $item->total_price,
                        ]);
                    }

                    Notification::make()
                        ->title('Success')
                        ->success()
                        ->body('Quotation converted to Invoice successfully!')
                        ->send();
                }),

            EditAction::make(),
        ];
    }
}
