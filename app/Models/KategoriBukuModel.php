<?php

namespace App\Models;

use App\Entities\KategoriBukuEntity;
use App\Exception\DatabaseExceptionNotFound;
use App\Exception\DatabaseFailedInsert;
use App\Libraries\LoggerCreations;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Database\Exceptions\DatabaseException;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Model;
use Monolog\Logger;

class KategoriBukuModel extends Model
{
    protected $table            = 'kategori_buku';
    protected $primaryKey       = 'id_kategori';
    protected $useAutoIncrement = false;
    protected $returnType       = KategoriBukuEntity::class;
    protected $useSoftDeletes   = false;
    protected $allowedFields    = [
        "id_kategori",
        "nama_kategori"
    ];


    // Validation
    protected $validationRules      = [
        "nama_kategori"=>"alpha_numeric_space|min_length[5]|max_length[100]|required"
    ];
    protected $validationMessages   = [
        "nama_kategori"=>
            [
                "alpha_numeric_space"=>"only alphabet, numeric and space is allowed for kategori",
                "min_length[5]"=>"minimum length of name is 5",
                "max_length[100]"=>"maximum length for name is 100",
                "required"=>"please input the name"
            ]
    ];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;
    private Logger $logger;

    public function __construct(ConnectionInterface $db)
    {
        parent::__construct($db);

        $this->logger = LoggerCreations::LoggerCreations(KategoriBukuModel::class);
    }


    /**
     * menambahkan 1 data ke table kategori_Buku
     *
     * method ini mencoba inserting data dengan di provide kategoriEntity object.
     * method ini dapat mengakibatkan exception gagal insert data
     * ---
     * gunakan kategoriBukuEntity->createObject sebagai penyedia pembuatan object kategori buku
     * @param KategoriBukuEntity $kategoriBukuEntity
     * @return void
     * @throws DatabaseFailedInsert --> insert failed data sudah ada di database
     */
    public function insertData(KategoriBukuEntity $kategoriBukuEntity): void
    {
        try {
            // menambahkan data
            $this->insert($kategoriBukuEntity);
            $this->logger->info("success insert data ".$kategoriBukuEntity);

        }//catch insert failed
        catch (\ReflectionException $e)
        {
            $this->logger->error("--------error on insert method--------");
            $this->logger->error($e->getMessage());

        }//catch data duplicate
        catch (DatabaseException $exception)
        {
            $this->logger->debug("-------------> duplicate entry Kategori Buku, failed Inserting data {$kategoriBukuEntity} <-------");
            $this->logger->debug($exception->getMessage());

            throw new DatabaseFailedInsert(
                "Failed Insert, kategori userDashboard sudah ada",
                ResponseInterface::HTTP_UNPROCESSABLE_ENTITY,
                $kategoriBukuEntity);
        }
    }


    /**
     * mencari nama kategori data di table kategori_buku
     *
     * method ini akan mencari data dengan param idKategori, ini dapat digunakan untuk mendapatkan
     * nama kategori dari table buku (di table buku terdapat id_kategori). method ini dapat
     * menyebabkan exception DataExceptionNotFound
     * @param string $idKategori
     * @return array|object object
     * @throws DatabaseExceptionNotFound data dengan id kategori tidak ditemukan
     */
    public function getNamaKategoriByIdKategori(string $idKategori): array|object
    {
        // mendapatkan data dari database, Note : ini menghasilkan Array object
        $result = $this->where("id_kategori",$idKategori)
                        ->find();

        if($result != null){
            // mendapatkan object data dari array object
            return $result[0];

        }else
        {
            $this->logger->debug("-------------> Kesalahan pada pencarian nama kategori buku <---------");
            $this->logger->debug("tidak menemukan data dengan id kategori ".$idKategori);

            throw new DatabaseExceptionNotFound
            (
                "kategori tidak ditemukan",
                ResponseInterface::HTTP_NOT_FOUND,
                $idKategori
            );
        }
    }

    /**
     * mendapatkan semua data table kategori buku
     *
     * method ini mengambil semua data yang ada di database dan
     * mengembalikannya dalam bentuk array of object, gunakan for each
     * dan attributes/data mapping kategori entity dari hasil kembalian method
     *
     * @return array mengembalikan array kosong jika data tidak ditemukan
     */
    public function getAllNamaKategori(): array
    {
        $queryResult = $this->findAll();

        // check data array, if the array is empty return empty of array
        if(count($queryResult)<1){
            $this->logger->debug("---------> data table kategori buku kosong Buku <--------");

        }
        return $queryResult;
    }
}
