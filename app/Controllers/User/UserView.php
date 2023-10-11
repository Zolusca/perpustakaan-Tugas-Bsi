<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;

/**
 * This class provides a page that is returned to the user
 * Class ini menyediakan halaman yang dikembalikan ke user
 */
class UserView extends BaseController
{
    public function registerform(){
        return view("user/RegisterForm");
    }

//    public function loginform(){
//        return
//    }

}