<?php

namespace App\Controllers;


use App\Models\PinjamModel;
use Config\Services;

/**
 * This class provides a page that is returned to the user
 * Class ini menyediakan halaman yang dikembalikan ke user
 */
class UserController extends BaseController
{
    public function getRegisterForm(): string
    {
        return view("register");
    }

    public function getLoginForm()
    {
        return  view("login");
    }

    public function home(){
        return redirect()->to(base_url()."user/login");
    }

    public function test(){
        $e = new PinjamModel(Services::getDatabaseConnection());
        return view("test",["data"=>$e->getUpdateTotalDendaJatuhTempoPengembalianUser("CtkW0CPg2Oh2JHp")]);
    }

}