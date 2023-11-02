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

    public function createPdf($dataParser,$namaFile,$layoutHtml){
        $this->dompdf->setPaper('A4','landscape');
        $this->dompdf->loadHtml(view($layoutHtml,$dataParser));
        $this->dompdf->render();
        $this->dompdf->stream($namaFile.".pdf");
    }

}