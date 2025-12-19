<?php

namespace Modules\Quotations\Services;

use Mpdf\Mpdf;
use Modules\Quotations\Models\Quotation;

class QuotationPdfService
{
    /**
     * Generate PDF for a quotation.
     *
     * @param Quotation $quotation
     * @param string $lang 'en' or 'ar'
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function generatePdf(Quotation $quotation, string $lang = 'en')
    {
        $mpdfConfig = [
            'mode' => $lang === 'ar' ? 'utf-8' : '',
            'format' => 'A4',
            'default_font' => $lang === 'ar' ? 'dejavusans' : 'helvetica',
        ];

        $mpdf = new Mpdf($mpdfConfig);

        // Load a simple HTML view
        $html = view('quotations.pdf', [
            'quotation' => $quotation,
            'lang' => $lang,
        ])->render();

        $mpdf->WriteHTML($html);

        $fileName = "Quotation-{$quotation->number}-{$lang}.pdf";

        return response()->streamDownload(function() use ($mpdf) {
            echo $mpdf->Output('', 'S');
        }, $fileName);
    }
}
