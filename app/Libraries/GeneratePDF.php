<?php

namespace App\Libraries;

use Dompdf\Dompdf;

class GeneratePDF
{
    private Dompdf $dompdf;


    public function __construct()
    {
        $this->dompdf = new Dompdf();
    }

    public function createPdf($dataParser){
        $this->dompdf->setPaper('A4','landscape');
        $this->dompdf->loadHtml(view("template/PdfOutput",$dataParser));
        $this->dompdf->render();
        $this->dompdf->stream("kartu_booking.pdf");
    }

}