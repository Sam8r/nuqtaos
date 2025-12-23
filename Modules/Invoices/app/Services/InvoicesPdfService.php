<?php

namespace Modules\Invoices\Services;

use Mpdf\Mpdf;
use Modules\Invoices\Models\Invoice;

class InvoicesPdfService
{
    /**
     * Generate PDF for an invoice.
     *
     * @param Invoice $invoice
     * @param string $lang 'en' or 'ar'
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function generatePdf(Invoice $invoice, string $lang = 'en')
    {
        $mpdfConfig = [
            'mode' => $lang === 'ar' ? 'utf-8' : '',
            'format' => 'A4',
            'default_font' => $lang === 'ar' ? 'dejavusans' : 'helvetica',
        ];

        $mpdf = new Mpdf($mpdfConfig);

        // Load the Invoice PDF view
        $html = view('invoices.pdf', [
            'invoice' => $invoice,
            'lang' => $lang,
        ])->render();

        $mpdf->WriteHTML($html);

        $fileName = "Invoice-{$invoice->number}-{$lang}.pdf";

        return response()->streamDownload(function () use ($mpdf) {
            echo $mpdf->Output('', 'S');
        }, $fileName);
    }
}
