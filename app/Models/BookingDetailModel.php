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

        $this->logger = LoggerCreations::LoggerCreations(BookingDetailModel::class);
    }


    /**
     * menambahkan data ke database dengan BookingDetailEntity
     *
     *
     * @param BookingDetailEntity $bookingDetailEntity
     * @return void
     * @throws DatabaseFailedInsert jika data pada bookingdetailentity ada yang bermasalah mungkin ketidak cocokan dengan table lain
     */
    public function insertData(BookingDetailEntity $bookingDetailEntity): void
    {
        try {
            // menambahkan data
            $this->insert($bookingDetailEntity);
            $this->logger->info("--------> success insert data ".$bookingDetailEntity);

        }// catch error insert
        catch (\ReflectionException $e )
        {
            $this->logger->error("--------> error inserting data booking detail <--------");
            $this->logger->error($e->getMessage());

        }//catch data duplicate
        catch (DatabaseException $exception){

            $this->logger->debug("--------> terdapat masalah dengan inserting data {$bookingDetailEntity} <--------");
            $this->logger->debug($exception->getMessage());

            throw new DatabaseFailedInsert(
                $exception->getMessage(),
                ResponseInterface::HTTP_UNPROCESSABLE_ENTITY,
                $bookingDetailEntity);
        }
    }


    /**
     * mencari data di table booking detail dengan id booking
     *
     * method ini mencari 1 data booking detail dengan idBooking dan mengembalikan
     * object yang datanya bisa diakses dengan attributes/datamap booking detail entity
     * @param string $idBooking
     * @return array|object
     * @throws DatabaseExceptionNotFound
     */
    public function getDataBookingDetailByIdBooking(string $idBooking): array|object
    {
        // mendapatkan data dari database, Note : ini menghasilkan Array object
        $result = $this->where("id_booking",$idBooking)->find();

        if($result != null){
            // mendapatkan object data dari array object
            return $result[0];
        }
        else{
            $this->logger->debug("Data tidak ditemukan Booking detail dengan id booking ".$idBooking);

            throw new DatabaseExceptionNotFound
            (
                "booking detail tidak ditemukan",
                ResponseInterface::HTTP_NOT_FOUND,
                $idBooking
            );
        }
    }


}
