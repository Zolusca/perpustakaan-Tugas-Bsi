<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;
use CodeIgniter\Config\Services;
use function PHPUnit\Framework\isEmpty;

class Login extends BaseController
{
    public function login()
    {
        $session    = Services::session();
        $email      = $this->request->getVar("email");
        $password   = $this->request->getVar("password");
        if($email&&$password != null){
            $session->set(["logged_in"=>true]);
        }
    }
}