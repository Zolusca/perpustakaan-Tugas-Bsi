<?php

namespace App\Models;

use App\Entities\TempEntity;
use App\Exception\DatabaseExceptionNotFound;
use App\Exception\DatabaseFailedInsert;
use App\Libraries\LoggerCreations;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Database\Exceptions\DatabaseException;
use CodeIgniter\HTTP\Response;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Model;
use Monolog\Logger;
use PHPUnit\Runner\ReflectionException;
use function PHPUnit\Framework\isEmpty;

class TempModel extends Model
{
    protected $table            = 'temp';
    protected $primaryKey       = 'id_temp';
    protected $useAutoIncrement = true;
    protected $returnType       = TempEntity::class;
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    =
        [
            "tgl_booking",
            "id_user",
            "id_buku"
        ];

    private Logger $logger;

    public function __construct(ConnectionInterface $db)
    {
        parent::__construct($db);

        $this->logger = LoggerCreations::LoggerCreations(TempModel::class);
    }

    /**
     * inserting data ke table temporary dengan object tempEntity
     *
     * method ini mencoba inserting data ke table temporary, dan mengecek apakah
     * data berhasil di insert dan memberikan exception DatabaseFailedInsert. method ini
     * juga menangkap kegagalan jika ada data dari parameter yang salah ( foreign key )
     * perlu di cek
     *
     * @param TempEntity $tempEntity
     * @return void
     * @throws DatabaseFailedInsert
     */
    public function insertData(TempEntity $tempEntity): void
    {
        try {
            $actionResult =  $this->insert($tempEntity);

            // jika hasil action false atau gagal
            if($actionResult === false){
                $this->logger->error("-------> ada kesalahan pada insertData, coba cek jaringan/database <---------");

                // throw data tidak dapat di insert, terjadi kesalahan
                throw new DatabaseFailedInsert
                (
                    "gagal insert data",
                    Response::HTTP_INTERNAL_SERVER_ERROR
                );
            }else{
                $this->logger->info(" berhasil insert data ke table temp {$tempEntity} ");
            }

        } catch (\ReflectionException $e) {
            $this->logger->error("--------> error on insert method temp model <--------");
            $this->logger->error($e->getMessage());

        } catch (DatabaseException $exception){
            $this->logger->error("-----------> ada kesalahan pada data (mungkin foreign key) {$tempEntity}<---------");
            $this->logger->error($exception->getMessage());
        }
    }

    /**
     * mencari semua data temporary table yang berkaitan dengan idUser
     *
     * method ini melakukan pencarian data temporary dari user dan melakukan query join
     * ke table buku untuk mendapatkan nama detail informasi data yang di pilih. Method ini
     * akan memberikan exception apabila data dengan param id user tidak ditemukan
     *
     * @param string $idUser
     * @return array data array of object dari table temporary gunakan foreach dan arrtibutes/datamap
     * dari tempentity ex : $data->id_temp
     * @throws DatabaseExceptionNotFound
     */
    public function findAllDataTempUserByIdUser(string $idUser){

        // mendapatkan data semua data dengan id user dan join dengan buku,
        // kembalian adalah array data temp join buku
       $data =   $this
                    ->where("id_user",$idUser)
                    ->join('buku','temp.id_buku=buku.id_buku')
                    ->findAll();

       // jika data kosong atau tidak ditemukan
       if(count($data)<1)
       {
           $this->logger->debug("---------> pencarian data tidak ditemukan dengan param {$idUser} <------------");

           throw new DatabaseExceptionNotFound
           (
             "data temp user tidak ditemukan",
             Response::HTTP_NOT_FOUND,
             $idUser
           );
       }
       else{
           // mengembalikan data array object
           $this->logger->debug("---------------> Data berhasil ditemukan findAllTemp param id user {$idUser} <--------------");
           return $data;
       }

    }


    /**
     * method ini mencari semua data dan menghitung data dari temporary yang dibuat user
     *
     * @param string $idUser
     * @return int banyaknya data yang dibuat user di table temp
     */
    public function countDataTempByIdUser(string $idUser)
    {
        // mencari banyaknya data
        $actionResult = $this->where("id_user",$idUser)->findAll();

        return count($actionResult);
    }

    /**
     * menghapus satu data temporary user dengan id temporary
     *
     * method ini mencoba menghapus data, dan mengembalikan exception DatabaseExceptionNotFound
     * jika data tidak ditemukan dan catch jika ada query yang salah
     *
     * @param string $idTemp
     * @return void
     * @throws DatabaseExceptionNotFound data temp tidak ditemukan
     */
    public function deleteDataByIdTemp(string $idTemp)
    {
        try {
            $resultAction = $this->delete($idTemp);

            // mengecek apakah hasil query true atau false
            if($resultAction === false){
                $this->logger->debug("---------> data tidak ditemukan dengan id {$idTemp} <--------");

                // throw exception data yang ingin di hapus tidak ditemukan
                throw new DatabaseExceptionNotFound
                (
                  "Data id temp tidak ditemukan di database",
                  Response::HTTP_BAD_REQUEST,
                  $idTemp
                );
            }else{
                $this->logger->debug("---------> berhasil detele data pada table temp dengan id {$idTemp} <-------------");
            }

        }
        // menangkap error query
        catch (DatabaseException $exception){
            $this->logger->error("--------> error query pada penghapusan data dengan id temp {$idTemp} <----------");
            $this->logger->error($exception->getMessage());
        }
    }

    /**
     * menghapus semua data temporary user dengan id temporary
     *
     *  method ini mencoba menghapus data, dan mengembalikan exception
     *  jika data tidak ditemukan dan catch jika ada query yang salah
     *
     * @param string $idUser
     * @return void
     * @throws DatabaseExceptionNotFound data dengan id user tidak ditemukan
     */
    public function deleteAllDataTempByIdUser(string $idUser){
        try {
            // mengapus data temp dengan limit 3 yang artinya semua booking temp user
            $queryResult = $this->where("id_user",$idUser)
                                ->limit(3)
                                ->delete();

            // jika hasil query false artinya data tidak ditemukan
            if($queryResult === false)
            {
                $this->logger->debug("---------> data tidak ditemukan idUser {$idUser} <------");
                // throw exception data yang ingin di hapus tidak ditemukan
                throw new DatabaseExceptionNotFound
                (
                    "Data id temp tidak ditemukan di database",
                    Response::HTTP_BAD_REQUEST,
                    $idUser
                );
            }else{
                $this->logger->debug("---------> berhasil menghapus semua data di table temp dengan id user {$idUser}<-----------");
            }
        }catch (DatabaseException $exception){
            $this->logger->error("-------> ada kesalahan ketika mendelete semua data temp dengan id user {$idUser} <--------");
            $this->logger->error($exception->getMessage());
        }
    }

    /**
     * pengecekan data temporary duplikat ketika user melakukan aksi booking
     * pada data buku, yang diharapkan hanya user hanya klik 1x tombol booking buku
     *
     * @param string $idUser
     * @param string $idBuku
     * @return bool true (user di izinkan insert data), false (user telah insert data buku yang sama)
     */
    public function checkDuplicateTempBooking(string $idUser,string $idBuku){
        $resultQuery = $this->where([
                                        'id_user'=>$idUser,
                                        'id_buku'=>$idBuku
                                    ])
                            ->find();

        // data tidak ditemukan, user boleh insert data temp
        if(count($resultQuery)<1){
            $this->logger->debug("--------> data duplikat tidak ditemukan <-----------");
            return true;
        }
        // user telah klik 1x tombol booking
        else{
            $this->logger->debug("---------> user sudah membuat data temp dengan buku yang sama <-----------");
            return false;
        }
    }

}
