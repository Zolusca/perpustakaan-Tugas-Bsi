<?php

namespace App\Controllers\Dashboard;

use App\Controllers\BaseController;
use App\Entities\BookingDetailEntity;
use App\Entities\BookingEntity;
use App\Entities\BukuEntity;
use App\Entities\DetailPinjamEntity;
use App\Entities\PinjamEntity;
use App\Entities\TempEntity;
use App\Exception\DatabaseConnectionFull;
use App\Models\BookingDetailModel;
use App\Models\BookingModel;
use App\Models\BukuModel;
use App\Models\DetailPinjamModel;
use App\Models\KategoriBukuModel;
use App\Models\PinjamModel;
use App\Models\TempModel;
use App\Models\UserModel;
use CodeIgniter\Database\BaseConnection;
use CodeIgniter\Database\Exceptions\DatabaseException;
use CodeIgniter\HTTP\Response;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;

class Dashboard extends BaseController
{
    protected BaseConnection $databaseConnection;
    protected ResponseInterface $httpClientResponses;
    protected BukuEntity $bukuEntity;
    protected BukuModel $bukuModel;
    protected KategoriBukuModel $kategoriBukuModel;
    protected UserModel $userModel;
    protected TempModel $tempModel;
    protected TempEntity $tempEntity;
    protected BookingEntity $bookingEntity;
    protected BookingDetailEntity $bookingDetailEntity;
    protected PinjamEntity $pinjamEntity;
    protected DetailPinjamEntity $detailPinjamEntity;
    protected BookingModel $bookingModel;
    protected BookingDetailModel $bookingDetailModel;
    protected PinjamModel $pinjamModel;
    protected DetailPinjamModel $detailPinjamModel;

    public function __construct()
    {
        // setting up field
        $this->bukuEntity           =   new BukuEntity();
        $this->tempEntity           =   new TempEntity();
        $this->bookingEntity        =   new BookingEntity();
        $this->bookingDetailEntity  =   new BookingDetailEntity();
        $this->pinjamEntity         =   new PinjamEntity();
        $this->detailPinjamEntity   =   new DetailPinjamEntity();
        $this->httpClientResponses  =   \Config\Services::response();

        try{
            $this->databaseConnection = Services::getDatabaseConnection();
            $this->userModel          = new UserModel($this->databaseConnection);
            $this->bukuModel          = new BukuModel($this->databaseConnection);
            $this->tempModel          = new TempModel($this->databaseConnection);
            $this->bookingModel       = new BookingModel($this->databaseConnection);
            $this->bookingDetailModel = new BookingDetailModel($this->databaseConnection);
            $this->kategoriBukuModel  = new KategoriBukuModel($this->databaseConnection);
            $this->pinjamModel        = new PinjamModel($this->databaseConnection);
            $this->detailPinjamModel  = new DetailPinjamModel($this->databaseConnection);


        }catch (DatabaseConnectionFull|DatabaseException $exception)
        {
            $dataParser =
                [
                    "cause"=>$exception->getMessage()
                ];

            // sent response for user, database connection problem
            $this->httpClientResponses
                ->setStatusCode(Response::HTTP_SERVICE_UNAVAILABLE)
                ->setBody(view("errors/CustomError",$dataParser))
                ->send();
            exit();
        }

    }


    /**
     * method ini digunakan pada dasboard admin, untuk menangani data yang diperlukan
     * pada halaman (data buku), dimana kembalian dalam bentuk array yang akan di
     * berikan ke view('adminbuku').
     *
     * untuk parameter digunakan apabila ada data tambahan yang ingin digabung
     * dalam satu array, memudahkan dalam pengiriman data ke view dalam bentuk array.
     *
     * @param array $dataTambahan
     * @return array
     */
    public function getArrayDatabuku(array $dataTambahan=[]){
        // mengecek apakah ada data tambahan
        if (count($dataTambahan)<1){
            return
                [
                    "buku"=>$this->bukuModel->getAllDataBuku(),
                    "kategori"=>$this->kategoriBukuModel->getAllNamaKategori(),
                ];
        }else{
            return
                [
                    "buku"=>$this->bukuModel->getAllDataBuku(),
                    "kategori"=>$this->kategoriBukuModel->getAllNamaKategori(),
                    "dataError"=>$dataTambahan
                ];
        }

    }
    public function setTanggalInput(string $datetime): ?string
    {
        try {
            $date = new \DateTime($datetime, new \DateTimeZone('Asia/Jakarta'));
            return $date->format('Y-m-d');

        } catch (\Exception $e) {
            $this->logger->error("error when setting date time on setTanggalInput");
            $this->logger->error($e->getMessage());
            return null;
        }
    }
}