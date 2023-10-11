<?php

namespace App\Models;
//TODO
// database pinjam harus 1 orang bisa beberapa kali penjinjaman

use App\Entities\PinjamEntity;
use App\Exception\DatabaseExceptionNotFound;
use App\Exception\DatabaseFailedInsert;
use App\Libraries\LoggerCreations;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Database\Exceptions\DatabaseException;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Model;
use Monolog\Logger;

class PinjamModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'pinjam';
    protected $primaryKey       = 'no_pinjam';
    protected $useAutoIncrement = false;
    protected $returnType       = PinjamEntity::class;
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    =
        [
            "no_pinjam","tgl_pinjam",
            "id_booking","id_user",
            "tgl_kembali","tgl_pengembalian",
            "status","total_denda"
        ];

    // Validation
    protected $validationRules      =
        [
            "tgl_pinjam"=>"min_length[10]|max_length[20]|required",
            "tgl_kembali"=>"min_length[10]|max_length[10]|required",
            "tgl_pengembalian"=>"min_length[10]|max_length[10]|required",
            "total_denda"=>"min_length[1]|required",
        ];
    protected $validationMessages   =
        [
            "tgl_pinjam"=>[
                "min_length[10]"=>"minimal panjang 10",
                "max_length[20]"=>"maksimum panjang 20",
                "required"=>"harus diisi"
            ],
            "tgl_kembali"=>[
                "min_length[10]"=>"minimal panjang 10",
                "max_length[10]"=>"maksimum panjang 10",
                "required"=>"harus diisi"
            ],
            "tgl_pengembalian"=>[
                "min_length[10]"=>"minimal panjang 10",
                "max_length[10]"=>"maksimum panjang 10",
                "required"=>"harus diisi"
            ],
            "total_denda"=>[
                "min_length[1]"=>"minimal panjang 1",
                "required"=>"harus diisi"
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
     * @param PinjamEntity $pinjamEntity
     * @return void
     */
    public function insertData(PinjamEntity $pinjamEntity): void
    {
        try {
            // menambahkan data
            $this->insert($pinjamEntity);
            $this->logger->info("success insert data ".$pinjamEntity);

        }//catch insert failed
        catch (\ReflectionException $e )
        {
            $this->logger->error("--------error on insert method--------");
            $this->logger->error($e->getMessage());

        }//catch data duplicate
        catch (DatabaseException $exception){
            // pada database field table tidak ada yang unique jadi kesalahan duplicate tidak terjadi
            // maka dari itu kita gunakan getMessage()
            throw new DatabaseFailedInsert(
                $exception->getMessage(),
                ResponseInterface::HTTP_UNPROCESSABLE_ENTITY,
                $pinjamEntity);
        }
    }


    /**
     * mencari semua data buku dengan judul buku
     * @param string $idUser
     * @return array|object
     * @throws DatabaseExceptionNotFound data not exist
     */
    public function getDataPinjamByIdUser(string $idUser): array|object
    {
        // mendapatkan data dari database, Note : ini menghasilkan Array object
        $result = $this->asArray()->where("id_user", $idUser)->find();

        if ($result != null) {
            // mendapatkan object data dari array object
            return $result;
        } else {
            throw new DatabaseExceptionNotFound
            (
                "data pinjam tidak ditemukan",
                ResponseInterface::HTTP_NOT_FOUND,
                $idUser
            );
        }
    }
}
