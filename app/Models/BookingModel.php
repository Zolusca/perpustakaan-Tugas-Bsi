<?php

namespace App\Models;

use App\Entities\BookingEntity;
use App\Exception\DatabaseExceptionNotFound;
use App\Exception\DatabaseFailedInsert;
use App\Libraries\LoggerCreations;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Database\Exceptions\DatabaseException;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Model;
use Monolog\Logger;

class BookingModel extends Model
{
    protected $table            = 'booking';
    protected $primaryKey       = 'id_booking';
    protected $useAutoIncrement = false;
    protected $returnType       = BookingEntity::class;
    protected $useSoftDeletes   = false;
    protected $allowedFields    =
        [
            "id_booking","tgl_booking",
            "batas_ambil","id_user"
        ];


    // Validation
    protected $validationRules      =
        [
            "tgl_booking"=>"min_length[10]|max_length[10]|required",
            "batas_ambil"=>"min_length[10]|max_length[10]|required"
        ];
    protected $validationMessages   = [
        "tgl_booking"=>[
            "min_length[10]"=>"minimum length 10",
            "max_length[10]"=>"maximum length 10",
            "required"=>"tanggal wajib diisi"
        ],
        "batas_ambil"=>[
            "min_length[10]"=>"minimum length 10",
            "max_length[10]"=>"maximum length 10",
            "required"=>"tanggal wajib diisi"
        ]
    ];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;
    private Logger $logger;

    public function __construct(ConnectionInterface $db)
    {
        parent::__construct($db);

        $this->logger = LoggerCreations::LoggerCreations(BukuModel::class);
    }

    /**
     * menambahkan data ke database
     * @param BookingEntity $bookingEntity
     * @return void
     * @throws DatabaseFailedInsert insert failed data available on database
     */
    public function insertData(BookingEntity $bookingEntity): void
    {
        try {
            // menambahkan data
            $this->insert($bookingEntity);
            $this->logger->info("success insert data ".$bookingEntity);

        }// catch error insert
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
                $bookingEntity);
        }
    }

    /**
     * mencari data booking with id user
     * @param string $idUser
     * @return array|object
     */
    public function getBookingByIdUser(string $idUser): array|object
    {
        // mendapatkan data dari database, Note : ini menghasilkan Array object
        $result = $this->where("id_user",$idUser)->find();

        if($result != null){
            // mendapatkan object data dari array object
            return $result[0];
        }else{
            throw new DatabaseExceptionNotFound
            (
                "data booking tidak ditemukan",
                ResponseInterface::HTTP_NOT_FOUND,
                $idUser
            );
        }
    }


}
