<?php

namespace App\Controllers\User;

use Config\Services;
use App\Controllers\BaseController;
use App\Entities\UserEntity;
use App\Exception\DatabaseConnectionFull;
use App\Models\UserModel;
use CodeIgniter\Database\BaseConnection;
use CodeIgniter\Database\Exceptions\DatabaseException;
use CodeIgniter\HTTP\Response;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\View\Parser;


/**
 * This class provide data where
 **/
class User extends BaseController
{
    protected BaseConnection $databaseConnection;
    protected ResponseInterface $httpClientResponses;
    protected UserEntity $userEntity;
    protected UserModel $userModel;

    public function __construct()
    {
        // setting up field
        $this->userEntity           =   new UserEntity();
        $this->httpClientResponses  =   \Config\Services::response();

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
                ->setBody(view("errors/CustomError",$dataParser))
                ->send();
            exit();
        }
    }


}