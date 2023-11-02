<?php

namespace App\Controllers\Dashboard\Admin;

use App\Controllers\Dashboard\Dashboard;
use App\Entities\Enum\StatusPinjam;
use App\Exception\DatabaseExceptionNotFound;
use App\Exception\ValidationErrorMessages;
use App\Libraries\RandomString;
use CodeIgniter\Database\Exceptions\DatabaseException;
use CodeIgniter\HTTP\Response;
use CodeIgniter\Validation\Exceptions\ValidationException;
use Config\Services;

class DashboardAdmin extends Dashboard
{
    /**
     * mehtod get
     * ini untuk path admin/dashboard/main
     */
    public function tampilanAdminBuku(){
        return view("dashboard/adminbuku",["databuku"=>$this->getArrayDatabuku()]);
    }

    /**
     * mehtod get
     * ini untuk path admin/dashboard/userbooking
     */
    public function tampilanAdminBooking(){
        return view("dashboard/adminbooking");
    }

    /**
     * mehtod get
     * ini untuk path admin/dashboard/userpeminjam
     */
    public function tampilanAdminPeminjam(){
        return view('dashboard/adminlistuserpinjam');
    }

    /**
     * method post
     * ini untuk path admin/dashboard/tambahbuku
     */
    public function adminTambahBukuAction(){
        $judulBuku  = $this->request->getVar("judulbuku");
        $kategori   = $this->request->getVar("kategori");
        $pengarang  = $this->request->getVar("pengarang");
        $penerbit   = $this->request->getVar("penerbit");
        $tahunTerbit= $this->request->getVar("tahunterbit");
        $stok       = $this->request->getVar("stok");
        $gambar     = $this->request->getFile("gambar");

        // mengubah nama dari file gambar
        $namaGambar = RandomString::random_string(9).".".$gambar->getClientExtension();


        try {
            $this->bukuEntity->createObject
            (
                $judulBuku,$kategori,$pengarang,
                $penerbit,(int)$tahunTerbit,$stok,
                $namaGambar
            );

            // inserting data ke table
            $this->bukuModel->insertData($this->bukuEntity);

            // memindahkan gambar ke public/userprofilepicture dengan nama random
            // FCPATH direktori absolute dari /home/namauser/...
            $gambar->move(FCPATH."/bukupicture/",$namaGambar);

            return redirect()->to(base_url().'admin/dashboard/main');

        }catch (ValidationErrorMessages $exception){
            $dataError = array_merge([$exception->getMessage()],$this->bukuModel->errors());

            $this->httpClientResponses
                ->setStatusCode(Response::HTTP_BAD_REQUEST)
                ->setBody(view("dashboard/adminbuku",["databuku"=>$this->getArrayDatabuku($dataError)]))
                ->send();
        } finally {
            Services::closeDatabaseConnection($this->databaseConnection);
        }
        return null;
    }

    /**
     * method post
     * ini untuk path admin/dashboard/cariboooking
     * @return void
     */
    public function adminSeacrhUserBooking(){
        $email = $this->request->getVar("email");

        try{
            // mendapatkan data user
            $dataUser = $this->userModel->getUserByEmail($email);

            // mendapatkan data detail dari booking user
           $dataUserBooking = $this->bookingModel
                                    ->getAllBookingUserJoinTableUserTableBukuTableKategori($dataUser->id_user);


            $this->httpClientResponses
                ->setStatusCode(Response::HTTP_OK)
                ->setBody(view("dashboard/adminbooking",["databooking"=>$dataUserBooking]))
                ->send();

        }catch (DatabaseExceptionNotFound|DatabaseException $exception){

            $this->httpClientResponses
                ->setStatusCode(Response::HTTP_NOT_FOUND)
                ->setBody(view("errors/CustomError",["cause"=>$exception->getMessage()]))
                ->send();

        } finally {
            Services::closeDatabaseConnection($this->databaseConnection);
        }
    }

    /**
     * method post
     * ini untuk path admin/dashboard/userambilbuku
     */
    public function adminAksiUserBookingToPinjam(){

        $denda      = $this->request->getVar(["denda"]);
        $lamaPinjam = $this->request->getVar(["tanggal"]);
        $idBooking  = $this->request->getVar(["idbooking"]);
        $idBuku     = $this->request->getVar(["idbuku"]);
        $idUser     = $this->request->getVar("iduser");


        $arrayDenda      = $denda["denda"];
        $arrayLamaPinjam = $lamaPinjam["tanggal"];
        $arrayIdBooking  = $idBooking["idbooking"];
        $arrayIdBuku     = $idBuku["idbuku"];

        try{
            // looping data untuk pembuatan object pinjamEntity dan detail pinjamEntity
            // serta inserting kedua table tsb, dan updating data dibooking dan dipinjam pada table buku
            // $index ini merupakan nilai indexing array 0->dst
            foreach ($arrayDenda as $index=>$value){

                $this->pinjamEntity->createObject
                (
                    $arrayLamaPinjam[$index],
                    $idUser,$arrayIdBooking[$index]
                );
                $this->pinjamModel->insertData($this->pinjamEntity);

                //-----------> persiapan detail pinjam <----------------//
                $objectPinjam = $this->pinjamModel->getDataPinjamByIdBooking($arrayIdBooking[$index]);

                $this->detailPinjamEntity->createObject
                (
                    $objectPinjam->no_pinjam,
                    $arrayIdBuku[$index],
                    $value
                );
                $this->detailPinjamModel->insertData($this->detailPinjamEntity);


                // mengupdate data dibooking dan dipinjam table buku
                $this->bukuModel->updateDecrementDataDiBookingFieldByIdBuku($arrayIdBuku[$index]);
                $this->bukuModel->updateIncrementDataDiPinjamFieldByIdBuku($arrayIdBuku[$index]);
            }

            // mengembalikan response
            $this->httpClientResponses
                ->setStatusCode(Response::HTTP_OK)
                ->setBody(view("dashboard/adminbooking",["dataResponse"=>"berhasil di update"]))
                ->send();

        }catch (ValidationErrorMessages|DatabaseException $exception){
            $this->httpClientResponses
                ->setStatusCode($exception->getHttpStatusCode())
                ->setBody(view("errors/CustomError",["cause"=>$exception]))
                ->send();
        }

    }

    /**
     * method post
     * ini untuk path admin/dashboard/caripeminjam
     */
    public function adminSearchUserPeminjam(){
        $email = $this->request->getVar("email");

        try{

            $dataUser = $this->userModel->getUserByEmail($email);

            // mengecek jatuh tempo pinjam user
            $this->pinjamModel->getUpdateTotalDendaJatuhTempoPengembalianUser($dataUser->id_user);

            // mengambil data pinjam user
            $dataPinjamUser = $this->pinjamModel
                                    ->getAllDataPinjamUserJoinTableBuku($dataUser->id_user);

            $result=[
                'user'=>$dataUser,
                'datapinjaman'=>$dataPinjamUser
            ];

            $this->httpClientResponses
                ->setStatusCode(Response::HTTP_OK)
                ->setBody(view("dashboard/adminlistuserpinjam",["datauserpinjam"=>$result]))
                ->send();

        }catch (DatabaseExceptionNotFound|DatabaseException $exception){
            $this->httpClientResponses
                ->setStatusCode(Response::HTTP_NOT_FOUND)
                ->setBody(view("errors/CustomError",["cause"=>$exception->getMessage()]))
                ->send();
        } finally {
            Services::closeDatabaseConnection($this->databaseConnection);
        }
    }

    /**
     * @throws \ReflectionException
     */
    public function adminAksiUserKembalikanPinjam(){
        $tanggalPengembalian      = $this->request->getVar(["tanggal"]);
        $idBooking  = $this->request->getVar(["idbooking"]);
        $idBuku     = $this->request->getVar(["idbuku"]);
        $noPinjam   = $this->request->getVar(["nopinjam"]);

        $arrayTanggalPengembalian      = $tanggalPengembalian["tanggal"];
        $arrayIdBooking                = $idBooking["idbooking"];
        $arrayIdBuku                   = $idBuku["idbuku"];
        $arrayNoPinjam                 = $noPinjam['nopinjam'];

        foreach ($arrayIdBuku as $index=>$value){

            $this->pinjamModel->where('no_pinjam',$arrayNoPinjam[$index])
                              ->set('status',StatusPinjam::DIKEMBALIKAN->value)
                              ->set('tgl_pengembalian',$this->setTanggalInput($arrayTanggalPengembalian[$index]))
                              ->update();

            // Masalah pada hubungan database, on delete on cascade
//            $this->bookingModel->where('id_booking',$arrayIdBooking[$index])->delete();

            // Todo update pinjam pada buku model
        }
        // mengembalikan response
        $this->httpClientResponses
            ->setStatusCode(Response::HTTP_OK)
            ->setBody(view("dashboard/adminlistuserpinjam",["dataResponse"=>"berhasil di update"]))
            ->send();
    }

    public function adminAksiLihatdaftarAnggota(){
        return view(
            "dashboard/admindaftaranggota",
            [
                "dataanggota"=>[
                    "user"=>$this->userModel
                                ->where('role_user','anggota')
                                ->findAll(10)
                ]
            ]);
    }

}