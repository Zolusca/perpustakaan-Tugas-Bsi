<?php

namespace App\Models;

use App\Entities\Enum\StatusPinjam;
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

class PinjamModel extends Model
{
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

        $this->logger = LoggerCreations::LoggerCreations(PinjamModel::class);
    }

    /**
     * menambahkan data ke database
     * @param PinjamEntity $pinjamEntity
     * @return void
     * @throws ValidationErrorMessages gunakan getInformation untuk mendapatkan data erro array
     */
    public function insertData(PinjamEntity $pinjamEntity): void
    {
        try {
            // menambahkan data
            $resultQuery    = $this->insert($pinjamEntity);

            if($resultQuery === false){
                $this->logger->debug("----------> masalah pada insert, validasi exception <------------");

                // throw exception jika ada validasi error
                throw new ValidationErrorMessages(
                    "validation message exception",
                    $this->errors()
                );
            }else{
                $this->logger->info("---------> success insert data ".$pinjamEntity);
            }

        }//catch insert failed
        catch (\ReflectionException $e )
        {
            $this->logger->error("--------error on insert method--------");
            $this->logger->error($e->getMessage());

        }//catch data duplicate
        catch (DatabaseException $exception){
            // pada database field table tidak ada yang unique jadi kesalahan duplicate tidak terjadi
            // maka dari itu kita gunakan getMessage()
            $this->logger->debug("---- pinjam model insert method----");
            $this->logger->debug($exception->getMessage());

            throw new DatabaseFailedInsert(
                $exception->getMessage(),
                ResponseInterface::HTTP_UNPROCESSABLE_ENTITY,
                $pinjamEntity);
        }
    }

    /**
     * mencari semua data userDashboard dengan judul userDashboard
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

    /**
     * @param string $idUser
     */
    public function getUpdateTotalDendaJatuhTempoPengembalianUser(string $idUser){
        try {
            // pencarian data dengan tanggal kembali buku terlambat dan status dipinjam
            // melakukan join ke table detail_pinjam untuk mengambil denda per buku
            $resultQuery = $this->where('status','dipinjam')
                                ->where('pinjam.id_user',$idUser)
                                ->where('tgl_kembali <',date('Y-m-d'))
                                ->join('detail_pinjam','pinjam.no_pinjam = detail_pinjam.no_pinjam')
                                ->findAll();

            /// for each hasil dan merubah data total_denda pada table pinjam
            foreach ($resultQuery as $item) {
                $this->where('no_pinjam',$item->no_pinjam)
                     ->set('total_denda',$item->denda)
                     ->update();
            }

            $this->logger->debug("--------> data berhasil di update update total denda<-----------");



        }catch (DatabaseException|\ReflectionException  $exception){
            $this->logger->debug("---- pinjam model update total denda  method----");
            $this->logger->debug($exception->getMessage());

            throw new DatabaseFailedInsert(
                $exception->getMessage(),
                ResponseInterface::HTTP_UNPROCESSABLE_ENTITY,
                $idUser);
        }

    }

    public function getAllDataPinjamUserJoinTableBuku(string $idUser){
        $resultQuery = $this->where('id_user',$idUser)
                            ->join('booking_detail','pinjam.id_booking = booking_detail.id_booking')
                            ->join('buku','booking_detail.id_buku = buku.id_buku')
                            ->findAll();
        return $resultQuery;

    }

    public function getDataPinjamByIdBooking(string $idBooking){
        $resultQuery    = $this->where('id_booking',$idBooking)
                                ->find();
        return $resultQuery[0];
    }
}
