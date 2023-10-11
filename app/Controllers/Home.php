<?php

namespace App\Controllers;


use App\Entities\BukuEntity;
use App\Entities\KategoriBukuEntity;
use App\Exception\DatabaseConnectionFull;
use App\Exception\DatabaseExceptionNotFound;
use App\Exception\DatabaseFailedInsert;
use App\Models\BukuModel;
use App\Models\KategoriBukuModel;
use Config\Services;

class Home extends BaseController
{
    public function sayhello(){
        echo "hooo";
    }
    public function index()
    {
        return view("App\Views\Home");
//        $parser         = Services::parser();
//        $responseData   = Services::response();
//
//        try {
            $databaseConnection = Services::getDatabaseConnection();
//
//            $bukuModel = new BukuModel($databaseConnection);
//            $bukuEntity = new BukuEntity();
//
//            $data = $bukuModel->findAllBukuByJudulBuku("pendongeng handal");
//
//            $dataParser =
//            [
//                "judul"=>"List buku",
//                "dataBuku"=>$data
//            ];
//
//            $responseData
//                    ->setStatusCode(200);
//            $responseData->send();
//
//
//            return $parser->setData($dataParser)->render('Home');
//
//        }catch (DatabaseExceptionNotFound $exception){
//            $responseData
//                ->setStatusCode($exception->getHttpStatusCode())
//                ->setBody($exception->getMessage());
//            $responseData->send();
//        } finally {
//            Services::closeDatabaseConnection($databaseConnection);
//        }

//        try {
//            $database = Services::getDatabaseConnection();
//
//            $userModel  = new UserModel($database);
//            echo $userModel->getUserByEmail("yumm@gmail.com");
//
//        } catch (DatabaseExceptionNotFound $e) {
//            $responseData
//                ->setStatusCode($e->getHttpStatusCode())
//                ->setBody($e->getMessage());
//            $responseData->send();
//
//        }catch (\Exception $e){
//
//        }
//        finally {
//            Services::closeDatabaseConnection($database);
//        }

    }
}
