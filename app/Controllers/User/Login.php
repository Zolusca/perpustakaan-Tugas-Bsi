<?php

namespace App\Controllers\User;

use App\Controllers\DashboardController;
use App\Entities\Enum\RoleUser;
use App\Exception\DatabaseExceptionNotFound;
use Config\Services;
use CodeIgniter\HTTP\Response;

class Login extends User
{
    public function __construct()
    {
        parent::__construct();
    }


    /**
     * Method ini menghandle method POST login, dimana akan ada pengecekan data
     * jika berhasil akan dikirim ke user/dashboard/buku dan diberikan session
     * logged_in = true dan email
     *
     */
    public function login()
    {
        $email      = $this->request->getVar("email");
        $password   = $this->request->getVar("password");

        try{
            // get user object and store on variable
            $data = $this->userModel->getUserByEmail($email);

            // checking the password input
            if($password    === $data->password)
            {
                // set the session of user to true and add the email session
                session()->set(["logged_in"=>true]);
                session()->set(["email"=>$email]);

                // user pindah ke user/dashboard/buku
                if($data->roleUser === RoleUser::ADMIN_USER->value){
                    return redirect()->to(base_url()."admin/dashboard/main");
                }
                else{
                    return redirect()->to(base_url()."user/dashboard/buku");
                }
            }
            else{
                // membuat data kirim bahwa password salah
                $dataParser["data"]=
                    [
                        "dataError"=>"password salah"
                    ];

                $this->httpClientResponses
                    ->setStatusCode(Response::HTTP_UNAUTHORIZED)
                    ->setBody(view("login",$dataParser))
                    ->send();
            }

        }catch (DatabaseExceptionNotFound $exception){
            // mengirim data user tidak ditemukan
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
}