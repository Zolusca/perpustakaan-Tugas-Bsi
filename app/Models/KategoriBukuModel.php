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
    protected $DBGroup          = 'default';
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
     * menambahkan data ke database
     * @param KategoriBukuEntity $kategoriBukuEntity
     * @return void
     * @throws DatabaseFailedInsert insert failed data available on database
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
        catch (DatabaseException $exception){
            throw new DatabaseFailedInsert(
                "Failed Insert, kategori buku sudah ada",
                ResponseInterface::HTTP_UNPROCESSABLE_ENTITY,
                $kategoriBukuEntity);
        }
    }


    /**
     * mencari data kategori buku dengan nama kategori
     * @param string $namaKategori
     * @return array|object object
     */
    public function getKategoriByNamaKategori(string $namaKategori): array|object
    {
        // mendapatkan data dari database, Note : ini menghasilkan Array object
        $result = $this->where("nama_kategori",$namaKategori)->find();

        if($result != null){
            // mendapatkan object data dari array object
            return $result[0];
        }else{
            throw new DatabaseExceptionNotFound
            (
                "kategori tidak ditemukan",
                ResponseInterface::HTTP_NOT_FOUND,
                $namaKategori
            );
        }
    }
}
