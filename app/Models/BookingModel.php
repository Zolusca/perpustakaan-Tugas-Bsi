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

        $this->logger = LoggerCreations::LoggerCreations(BookingModel::class);
    }

    /**
     * menambahkan data ke database dengan bookingEntity Object
     *
     * method ini mencoba menambahkan data ke table booking, dan dapat mengakibatkan
     * exception DatabaseFailedInsert
     *
     * @param BookingEntity $bookingEntity
     * @return void
     * @throws DatabaseFailedInsert
     */
    public function insertData(BookingEntity $bookingEntity): void
    {
        try {
            // menambahkan data
            $this->insert($bookingEntity);
            $this->logger->info(" success insert data ".$bookingEntity);

        }// catch error insert
        catch (\ReflectionException $e )
        {
            $this->logger->error("------------> error inserting data with data {$bookingEntity} <--------------");
            $this->logger->error($e->getMessage());

        }//catch data duplicate
        catch (DatabaseException $exception)
        {
            $this->logger->debug("-------> problem insert booking periksa kembali data insert dengan booking {$bookingEntity} <----------");
            $this->logger->debug("error code mysql ".$exception->getCode());
            $this->logger->debug($exception->getMessage());

            throw new DatabaseFailedInsert(
                $exception->getMessage(),
                ResponseInterface::HTTP_UNPROCESSABLE_ENTITY,
                $bookingEntity);
        }
    }

    /**
     * mencari 1 data booking yang cocok dengan id user
     *
     * method ini akan mencari data di table booking dengan idUser, dan mengembalikan array of object
     * method ini dapat mengakibatkan exception databasenotfound jika data berdasarkan
     * param tidak ditemukan.
     * ---
     * data array object dapat di akses menggunakan datamap/attributes pada entity
     * $result->id_booking
     *
     * @param string $idUser
     * @return array|object mengembalikan 1 data
     * @throws DatabaseExceptionNotFound data dengan param tersebut tidak ditemukan
     */
    public function getBookingByIdUser(string $idUser): array|object
    {
        // mendapatkan data dari database, Note : ini menghasilkan Array object
        $result = $this->where("id_user",$idUser)->find();

        if($result != null){
            $this->logger->debug("---------> data booking dengan id user {$idUser} ditemukan <-------");
            // mendapatkan object data dari array object
            return $result[0];
        }else{
            $this->logger->debug("------------> data booking dengan id user {$idUser} tidak ditemukan <-------");
            throw new DatabaseExceptionNotFound
            (
                "data booking tidak ditemukan",
                ResponseInterface::HTTP_NOT_FOUND,
                $idUser
            );
        }
    }

    /**
     * Dalam perbaikan
     * @param string $idUser
     * @return array
     */
    public function getAllDataBookingUser(string $idUser){
        try {
            $resultQuery = $this->where('id_user',$idUser)
                                ->findAll();
            return $resultQuery;
        }catch (DatabaseException $exception){
            $this->logger->error("--------> gagal query untuk mendapatkan semua data booking user <--------------");
            $this->logger->error( $exception->getMessage());
        }
        return [];
    }

    /**
     * mendaptkan semua data yang ada pada table booking limit 20
     *
     * @return array kosong jika data pada table kosong
     */
    public function getAllDataBooking(){
        $queryResult = $this->findAll(20);

        // data kosong
        if(count($queryResult)<1){
            $this->logger->debug("---------> data pada booking table kosong <---------");
        }else{
            $this->logger->debug("---------> data pada booking table ditemukan sejumlah" .count($queryResult)." <---------");
        }
        return $queryResult;
    }

    /**
     * pencarian data dengan query join (table : booking_detail,user,buku,Kategori_buku)
     * method ini mencoba mendapatkan data dari query join, dengan param idUser
     * ---
     * Note: disarankan cari data booking user terlebih dahulu sebelum menggunakan ini. Karena method
     * ini tidak akan mencari data booking user
     *
     * @param string $idUser
     * @return array
     */
    public function getAllBookingUserJoinTableUserTableBukuTableKategori(string $idUser)
    {
        $data = $this->where('booking.id_user',$idUser)
            ->join('booking_detail','booking.id_booking = booking_detail.id_booking')
            ->join('user','booking.id_user = user.id_user')
            ->join('buku','booking_detail.id_buku = buku.id_buku')
            ->join('kategori_buku','buku.id_kategori = kategori_buku.id_kategori')
            ->findAll();

        return $data;
    }
}
