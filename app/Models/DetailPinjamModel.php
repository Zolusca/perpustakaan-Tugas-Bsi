<?php

namespace App\Models;

use App\Entities\BukuEntity;
use App\Entities\DetailPinjamEntity;
use App\Entities\PinjamEntity;
use App\Exception\DatabaseExceptionNotFound;
use App\Exception\DatabaseFailedInsert;
use App\Exception\ValidationErrorMessages;
use App\Libraries\LoggerCreations;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Database\Exceptions\DatabaseException;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Model;
use Monolog\Logger;

class DetailPinjamModel extends Model
{
    protected $table            = 'detail_pinjam';
    protected $primaryKey       = 'no_pinjam';
    protected $useAutoIncrement = false;
    protected $returnType       = DetailPinjamEntity::class;
    protected $useSoftDeletes   = false;
    protected $allowedFields    =
        [
            "no_pinjam",
            "id_buku",
            "denda"
        ];
    protected $skipValidation       = true;
    private Logger $logger;

    public function __construct(ConnectionInterface $db)
    {
        parent::__construct($db);

        $this->logger = LoggerCreations::LoggerCreations(DetailPinjamModel::class);
    }

    /**
     * menambahkan data ke database
     * @param DetailPinjamEntity $detailPinjamEntity
     * @return void
     */
    public function insertData(DetailPinjamEntity $detailPinjamEntity): void
    {
        try {
            // menambahkan data
            $resultQuery    = $this->insert($detailPinjamEntity);

            $this->logger->info("---------> success insert data ".$detailPinjamEntity);

        }//catch insert failed
        catch (\ReflectionException $e )
        {
            $this->logger->error("--------error on insert method--------");
            $this->logger->error($e->getMessage());

        }//catch data duplicate
        catch (DatabaseException $exception){
            // pada database field table tidak ada yang unique jadi kesalahan duplicate tidak terjadi
            // maka dari itu kita gunakan getMessage()
            $this->logger->debug("---- detail pinjam model insert method----");
            $this->logger->debug($exception->getMessage());

            throw new DatabaseFailedInsert(
                $exception->getMessage(),
                ResponseInterface::HTTP_UNPROCESSABLE_ENTITY,
                $detailPinjamEntity);
        }
    }

    /**
     * mencari semua data userDashboard dengan judul userDashboard
     * @param string $noPinjam
     * @return array|object
     * @throws DatabaseExceptionNotFound data not exist
     */
    public function getDataDetailPinjamByNoPinjam(string $noPinjam): array|object
    {
        // mendapatkan data dari database, Note : ini menghasilkan Array object
        $result = $this->where("no_pinjam", $noPinjam)->find();

        if ($result != null) {
            // mendapatkan object data dari array object
            return $result;
        } else {
            throw new DatabaseExceptionNotFound
            (
                "data pinjam tidak ditemukan",
                ResponseInterface::HTTP_NOT_FOUND,
                $noPinjam
            );
        }
    }
}