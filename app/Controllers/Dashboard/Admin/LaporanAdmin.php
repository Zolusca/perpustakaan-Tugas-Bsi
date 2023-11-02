<?php

namespace App\Controllers\Dashboard\Admin;

use App\Controllers\Dashboard\Dashboard;
use App\Libraries\GeneratePDF;

class LaporanAdmin extends Dashboard
{
    public function laporanListUser(){
        // membuat data untuk diisi didalam view pdf
        $dataParser["userData"]=
            [
                $this->userModel->where('role_user','anggota')->findAll()
            ];
        // memproses pembuatan pdf
        $generatePdf = new GeneratePDF();
        $generatePdf->createPdf($dataParser,'laporanuser',"template/PdfLaporanUser");
    }

    public function laporanListUserPeminjam(){
        // membuat data untuk diisi didalam view pdf
        $dataParser["pinjamData"]=
            [
                $this->pinjamModel->join('user','pinjam.id_user = user.id_user')
                                 ->join('booking_detail','pinjam.id_booking = booking_detail.id_booking')
                                 ->join('buku','booking_detail.id_buku = buku.id_buku')
                                 ->findAll()
            ];

        // memproses pembuatan pdf
        $generatePdf = new GeneratePDF();
        $generatePdf->createPdf($dataParser,'laporanuser',"template/PdfLaporanPeminjam");
    }
}