<?php

namespace App\Models;

use App\Entities\BookingDetailEntity;
use App\Entities\BookingEntity;
use App\Exception\DatabaseExceptionNotFound;
use App\Exception\DatabaseFailedInsert;
use App\Libraries\LoggerCreations;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Database\Exceptions\DatabaseException;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Model;
use Monolog\Logger;

class BookingDetailModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'booking_detail';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = false;
    protected $returnType       = BookingEntity::class;
    protected $useSoftDeletes   = false;
    protected $allowedFields    =
        [
            "id",
            "id_booking",
            "id_buku"
        ];
    protected $skipValidation       = true;
    protected $cleanValidationRules = true;
    private Logger $logger;

    public function __construct(ConnectionInterface $db)
    {
        parent::__construct($db);

        $this->logger = LoggerCreations::LoggerCreations(KategoriBukuModel::class);
    }


    /**
     * menambahkan data ke database
     * @param BookingDetailEntity $bookingDetailEntity
     * @return void
     */
    public function insertData(BookingDetailEntity $bookingDetailEntity): void
    {
        try {
            // menambahkan data
            $this->insert($bookingDetailEntity);
            $this->logger->info("success insert data ".$bookingDetailEntity);

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
                $bookingDetailEntity);
        }
    }


    /**
     * mencari data booking detail dengan id booking
     * @param string $idBooking
     * @return array|object
     */
    public function getBookingDetailByIdBooking(string $idBooking): array|object
    {
        // mendapatkan data dari database, Note : ini menghasilkan Array object
        $result = $this->where("id_booking",$idBooking)->find();

        if($result != null){
            // mendapatkan object data dari array object
            return $result[0];
        }else{
            throw new DatabaseExceptionNotFound
            (
                "booking detail tidak ditemukan",
                ResponseInterface::HTTP_NOT_FOUND,
                $idBooking
            );
        }
    }
}
