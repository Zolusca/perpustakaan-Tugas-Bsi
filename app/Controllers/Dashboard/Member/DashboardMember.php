<?php

namespace App\Controllers\Dashboard\Member;

use App\Controllers\Dashboard\Dashboard;
use App\Exception\DatabaseExceptionNotFound;
use App\Exception\DatabaseFailedInsert;
use App\Libraries\GeneratePDF;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\Response;
use Config\Services;

class DashboardMember extends Dashboard
{
    /**
     * method get
     * menampilkan dashboard untuk member user dengan tampilan
     * list data buku
     * @return string
     */
    public function tampilanDashboardHome()
    {
        // mendapatkan data buku dan menyimpan di array dalam bentuk object
        $dataListBuku["dataListBuku"] =
            [
                $this->bukuModel->getAllDataBuku()
            ] ;

        return view("dashboard/memberdashboard",$dataListBuku);
    }

    /**
     * method get
     * menampilkan data detail buku ke user member
     * @param string $idBuku
     * @return void
     */
    public function tampilanDetailBuku(string $idBuku){

        try{
            // mencari data buku dengan id buku
            $objectBuku          = $this->bukuModel->findBukuByIdBuku($idBuku);

            // mencari nama jenis kategori dari buku
            $objectKategoriBuku  = $this->kategoriBukuModel->getNamaKategoriByIdKategori($objectBuku->idKategori);

            // mempersiapkan data detail untuk ditampilkan
            $dataParser["dataBuku"] =
                [
                    "kategoriBuku"  =>$objectKategoriBuku->namaKategori,
                    "judulBuku"     =>$objectBuku->judulBuku,
                    "pengarang"     =>$objectBuku->pengarang,
                    "penerbit"      =>$objectBuku->penerbit,
                    "tahunTerbit"   =>$objectBuku->tahunTerbit,
                    "isbn"          =>$objectBuku->isbn,
                    "stok"          =>$objectBuku->stok,
                    "dipinjam"      =>$objectBuku->dipinjam,
                    "gambar"        =>$objectBuku->gambar
                ];

            $this->httpClientResponses
                ->setStatusCode(Response::HTTP_OK)
                ->setBody(view("dashboard/bukudetail",$dataParser))
                ->send();

        }catch (DatabaseExceptionNotFound $exception){

            $dataParser =
                [
                    "cause"=>$exception->getMessage()
                ];

            $this->httpClientResponses
                ->setStatusCode(Response::HTTP_SERVICE_UNAVAILABLE)
                ->setBody(view("errors/CustomError",$dataParser))
                ->send();
        }
        finally {
            Services::closeDatabaseConnection($this->databaseConnection);
        }
    }

    /**
     * method post
     * untuk handle booking buku ( TAHAP TEMPORARY BOOKING )
     * aksi yang di handle ketika user klik tombol booking pada menu data buku
     */
    public function handlerUserBookingBuku()
    {
        // mengambil id buku dari tombol submit DashboardHome
        $idBuku = $this->request->getVar("idBuku");

        // mengambil email dari session
        $email = session()->get("email");

        try{
            // mencari data user melalui email untuk mendapatkan id user
            $objectUser = $this->userModel->getUserByEmail($email);

            // mencari banyaknya data temp booking oleh user, maks 3 booking temp. return int
            $result     = $this->tempModel->countDataTempByIdUser($objectUser->id_user);

            // pengecekan agar user tidak boleh klik booking lebih dari 1x untuk buku yang sama
            $boolResult = $this->tempModel->checkDuplicateTempBooking($objectUser->id_user,$idBuku);

            // pengecekan apakah user telah booking lebih dari 3 kali
            if($result>=3 || $boolResult === false){

                $dataParser =
                    [
                        "cause"=>"Batas booking hanya 3 buku dan 
                        tidak dapat memboking buku yang sama lebih dari 1x"
                    ];

                $this->response->setStatusCode(Response::HTTP_BAD_REQUEST);

                return view("errors/CustomError",$dataParser);

            }
            // lakukan aksi insert data
            else{
                // membuat object tempEntity
                $this->tempEntity->createObject
                (
                    $objectUser->idUser,
                    $idBuku
                );

                // insert action
                $this->tempModel->insertData($this->tempEntity);

                return redirect()->to(base_url()."user/dashboard/buku");
            }
        }
        // catch terjadi masalah gagal insert atau data tidak ditemukan
        catch (DatabaseFailedInsert|DatabaseExceptionNotFound $exception)
        {
            //   get value exception
            $dataParser =
                [
                    "cause"=>$exception->getMessage()
                ];

            //kirim response
            $this->httpClientResponses
                ->setStatusCode($exception->getHttpStatusCode())
                ->setBody(view("errors/CustomError",$dataParser))
                ->send();
        }
        finally {
            Services::closeDatabaseConnection($this->databaseConnection);
        }
        return null;
    }

    /**
     * method get
     * untuk menghandle data temporary list booking user, dimana ini akan
     * menampilkan data buku yang telah dibooking(Temporary Mode) oleh user
     * @return void
     */
    public function tampilanListDataBookingUser(){
        // mengambil email dari session
        $emailUser = session()->get("email");

        try{
            // mencari data user melalui email untuk mendapatkan id user
            $objectUser = $this->userModel->getUserByEmail($emailUser);

            // mendapatkan semua data pada table temp
            $data =  $this->tempModel->findAllDataTempUserByIdUser($objectUser->id_user);

            // mempersiapkan data untuk view
            $dataParser["dataBooking"] =
                [
                    $data
                ];

            $this->httpClientResponses
                ->setStatusCode(Response::HTTP_OK)
                ->setBody(view("dashboard/bookinglist",$dataParser))
                ->send();

        }
        // jika kedua aksi getUserByEmail dan findDataTemp tidak ditemukan
        catch (DatabaseExceptionNotFound $exception){
            //   get value exception
            $dataParser =
                [
                    "cause"=>$exception->getMessage()." Anda belum membooking buku"
                ];

            //kirim response
            $this->httpClientResponses
                ->setStatusCode($exception->getHttpStatusCode())
                ->setBody(view("errors/CustomError",$dataParser))
                ->send();
        }
        finally {
            Services::closeDatabaseConnection($this->databaseConnection);
        }
    }

    /**
     * method get
     * digunakan ketika user mendelete data Temporary Booking, method ini
     * akan mendelete data temporary table
     * @param string $idTemp
     * @return RedirectResponse|void
     */
    public function userDeleteDataBookingTemporary(string $idTemp){
        try {
            $this->tempModel->deleteDataByIdTemp($idTemp);

            return redirect()->to(base_url()."user/dashboard/buku/booking/list");

        }catch (DatabaseExceptionNotFound $exception){
            $dataParser["data"]=
                [
                    "dataError"=>$exception->getMessageException()
                ];

            $this->httpClientResponses
                ->setStatusCode(Response::HTTP_NOT_FOUND)
                ->setBody(view("login",$dataParser))
                ->send();
        } finally {
            Services::closeDatabaseConnection($this->databaseConnection);
        }
    }

    /**
     * method get
     *
     * method ini memproses inserting data booking dan booking_detail, serta memproses
     * output pdf
     * aksi tujuan inti :
     * - insert data booking, dimana membutuhkan data tgl booking dan id user
     *   tgl booking diambil dari table Temporary
     * - insert data booking detail, dimana membutuhkan data id booking dan id buku
     */
    public function prosesTemporayBookKeBookingDanDetailBooking(){

        $email          = session()->get('email');
        // array ini digunakan untuk mempermudah mengelola data input
        $arrayDataTglBooking =[];
        $arrayDataIdBuku     =[];
        $arrayDataIdBooking  =[];


        // mencari user dengan email dan mendapatkan idUser
        $userObject = $this->userModel->getUserByEmail($email);
        $idUser     = $userObject->id_user;

        // mendapatkan semua data table temporary
        $dataArrayTemp  = $this->tempModel->findAllDataTempUserByIdUser($userObject->id_user);

        // insert ke array keperluan data booking
        foreach ($dataArrayTemp as $value){
            $arrayDataTglBooking[] = $value->tglBooking;
            $arrayDataIdBuku[]     = $value->idBuku;
        }

        // looping data array tgl booking untuk pembuatan object booking entity
        foreach ($arrayDataTglBooking as $valueTglBooking)
        {
            $this->bookingEntity->createObject
            (
                $valueTglBooking,
                $idUser
            );

            $this->bookingModel->insertData($this->bookingEntity);
        }

        // untuk diambil id_booking keperluan table booking detail
        $dataArrayBooking = $this->bookingModel->getAllDataBookingUser($userObject->id_user);

        // push data ke array, agar mudah dikelola
        foreach ($dataArrayBooking as $value)
        {
            $arrayDataIdBooking[]= $value->id_booking;
        }


        // pembuatan detail_booking dan pengubahan data field di_booking pada table buku
        for($index=0 ; $index<count($arrayDataIdBuku);$index++)
        {
            // mengubah data di_booking field pada table buku
            $this->bukuModel->updateIncrementDataDiBookingFieldByIdBuku($arrayDataIdBuku[$index]);

            $this->bookingDetailEntity->createObject
            (
                $arrayDataIdBooking[$index],
                $arrayDataIdBuku[$index]
            );
            $this->bookingDetailModel->insertData($this->bookingDetailEntity);
        }


        // membuat data untuk diisi didalam view pdf
        $dataParser["bookingData"]=
            [
                $this->getDataBookingForPdf($userObject->id_user)
            ];

        // memproses pembuatan pdf
        $generatePdf = new GeneratePDF();
        $generatePdf->createPdf($dataParser);

        return view("template/PdfOutput",$dataParser);
    }

    public function getDataBookingForPdf(string $idUser){
        // mendapatkan semua data table buku, kategori buku, user
        return $this->bookingModel->getAllBookingUserJoinTableUserTableBukuTableKategori($idUser);
    }

}