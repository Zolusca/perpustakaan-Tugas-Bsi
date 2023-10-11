<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;
use App\Entities\UserEntity;
use App\Exception\DatabaseConnectionFull;
use App\Exception\DatabaseFailedInsert;
use App\Libraries\RandomString;
use App\Models\UserModel;
use CodeIgniter\Database\BaseConnection;
use CodeIgniter\Database\Exceptions\DatabaseException;
use CodeIgniter\HTTP\Response;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\View\Parser;
use Config\Services;

class Register extends BaseController
{
    private BaseConnection $databaseConnection;
    private ResponseInterface $httpClientResponses;
    private Parser $parserValue;
    private UserEntity $userEntity;
    private UserModel $userModel;

    public function __construct()
    {
        // setting up field
        $this->userEntity           =   new UserEntity();
        $this->httpClientResponses  =   Services::response();
        $this->parserValue          =   Services::parser();

        try{
            $this->databaseConnection = Services::getDatabaseConnection();
            $this->userModel          = new UserModel($this->databaseConnection);

        }catch (DatabaseConnectionFull|DatabaseException $exception)
        {
            $dataParser =
                [
                  "cause"=>$exception->getMessage()
                ];

            // sent response for user, database connection problem
            $this->httpClientResponses
                ->setStatusCode(Response::HTTP_SERVICE_UNAVAILABLE)
                ->setBody($this->parserValue->setData($dataParser)->render("errors/CustomError"))
                ->send();
            exit();
        }

    }


    /**
     * method registrasi user
     */
    public function register()
    {
        // get data client input
        $email  =   $this->request->getVar("email");
        $nama   =   $this->request->getVar("nama");
        $password   =   $this->request->getVar("password");
        $alamat     =   $this->request->getVar("alamat");
        $gambar     =   $this->request->getFile("gambar");

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

            // cek error validasi
            if(count($this->userModel->errors())>1)
            {
                // change name of data array value from [0] to ["dataError"]
                $dataErrorValidation["dataError"] = $this->userModel->errors();

                // add data parser and merge the array of error
                $dataParser["data"] =
                    [
                        $dataErrorValidation
                    ]  ;

                // return back form register and view data error
//                return view("user/RegisterForm",$dataParser);
                $this->httpClientResponses
                    ->setStatusCode(Response::HTTP_BAD_REQUEST)
                    ->setBody(view("user/RegisterForm",$dataParser))
                    ->send();

            }else
            {
                // sent response and save image when not any error inserting data
                $this->httpClientResponses
                    ->setStatusCode(Response::HTTP_OK)
                    ->setBody(view("welcome_message"))
                    ->send();

                $gambar->store("userprofile",$namaGambar);
            }

        }
        // catch exception when data can't insert
        catch (DatabaseFailedInsert $exception) {
          //   get value exception
            $dataParser =
                [
                    "cause"=>$exception->getMessage()
                ];

            // sent body a html with parser, and sent a exception
            $this->httpClientResponses
                ->setStatusCode(Response::HTTP_SERVICE_UNAVAILABLE)
                ->setBody($this->parserValue->setData($dataParser)->render("errors/CustomError"))
                ->send();
        }

    }

}