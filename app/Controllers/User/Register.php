<?php

namespace App\Controllers\User;

use App\Exception\DatabaseFailedInsert;
use App\Exception\ValidationErrorMessages;
use App\Libraries\RandomString;
use CodeIgniter\HTTP\Response;
use Config\Services;

class Register extends User
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * method registrasi user, mengambil data input user lalu mengubah nama file gambar.
     * selanjutnya dilakukan insert data.
     * penanganan throw yang terjadi adalah insert gagal karena data sudah ada
     * dan kesalahan validasi input user
     */
    public function register()
    {
        // get data client input
        $email  =   $this->request->getVar("email");
        $nama   =   $this->request->getVar("nama");
        $password   =   $this->request->getVar("password");
        $alamat     =   $this->request->getVar("alamat");
        $gambar     =   $this->request->getFile("gambar");


        // mempersiapkan nama untuk file gambar
        $namaGambar = RandomString::random_string(9).".".$gambar->getClientExtension();

        try{
            // creating user entity object with data input
            $this->userEntity->createObject
            (
              $nama,$alamat,
              $email,$namaGambar,$password
            );

            // insert data
            $this->userModel->insertData($this->userEntity);

            // memindahkan gambar ke public/userprofilepicture dengan nama random
            $gambar->move(FCPATH."/userprofilepicture/",$gambar);

        }
        // catch exception when data can't insert
        catch (DatabaseFailedInsert $exception)
        {
          //   get value exception
            $dataParser =
                [
                    "cause"=>$exception->getMessage()
                ];

            // sent body a html with parser, and sent a exception. with parser library
            $this->httpClientResponses
                ->setStatusCode(Response::HTTP_SERVICE_UNAVAILABLE)
                ->setBody(view("errors/CustomError",$dataParser))
                ->send();

        }
        // catch validation exception cause user input not valid
        catch (ValidationErrorMessages $exception)
        {
            // ambil data error message validation
            $messageValidation["dataError"]=$exception->getDataInformation();

            $dataParser["data"] =
                    [
                        $messageValidation
                    ]  ;

            $this->httpClientResponses
                ->setStatusCode(Response::HTTP_BAD_REQUEST)
                ->setBody(view("register",$dataParser))
                ->send();
        }
        finally
        {
            Services::closeDatabaseConnection($this->databaseConnection);
        }

        return redirect()->to(base_url()."user/login");
    }

}