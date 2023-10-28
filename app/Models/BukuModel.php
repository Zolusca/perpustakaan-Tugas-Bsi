<?php

namespace App\Models;

use App\Entities\BukuEntity;
use App\Exception\DatabaseExceptionNotFound;
use App\Exception\DatabaseFailedInsert;
use App\Exception\ValidationErrorMessages;
use App\Libraries\LoggerCreations;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Database\Exceptions\DatabaseException;
use CodeIgniter\Database\Exceptions\DataException;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Model;
use Monolog\Logger;
use PHPUnit\Runner\ReflectionException;

class BukuModel extends Model
{
    protected $table            = 'buku';
    protected $primaryKey       = 'id_buku';
    protected $useAutoIncrement = false;
    protected $returnType       = BukuEntity::class;
    protected $useSoftDeletes   = false;
    protected $allowedFields    =
    [
        "id_buku","judul_buku",
        "id_kategori","pengarang",
        "penerbit","tahun_terbit",
        "isbn","stok",
        "dipinjam","dibooking",
        "gambar",
    ];
    // Validation
    protected $validationRules      =
        [
            "judul_buku"=>"alpha_numeric_space|min_length[5]|max_length[100]|required",
            "pengarang"=>"alpha_numeric_space|min_length[5]|max_length[100]|required",
            "penerbit"=>"alpha_numeric_space|min_length[5]|max_length[100]|required",
            "tahun_terbit"=>"min_length[4]|max_length[4]|required",
            "stok"=>"is_natural|min_length[1]|max_length[5000]|required",
            "dipinjam"=>"is_natural|min_length[1]|max_length[5000]",
            "dibooking"=>"is_natural|min_length[1]|max_length[5000]",
            "gambar"=>"min_length[5]|max_length[60]"
        ];
    protected $validationMessages   =
        [
            "judul_buku"=>[
                "alpha_numeric_space"=>"only alphabet, numeric and space is allowed for judul",
                "min_length[5]"=>"minimum length of name is 5",
                "max_length[100]"=>"maximum length for name is 100",
                "required"=>"please input the judul"
            ],
            "pengarang"=>[
                "alpha_numeric_space"=>"only alphabet, numeric and space is allowed for nama pengarang",
                "min_length[5]"=>"minimum length of name is 5",
                "max_length[100]"=>"maximum length for name is 100",
                "required"=>"please input the nama pengarang"
            ],
            "penerbit"=>[
                "alpha_numeric_space"=>"only alphabet, numeric and space is allowed for nama penerbit",
                "min_length[5]"=>"minimum length of name is 5",
                "max_length[100]"=>"maximum length for name is 100",
                "required"=>"please input the nama penerbit"
            ],
            "tahun_terbit"=>[
                "min_length[4]"=>"minimum length of tahun is 4",
                "max_length[4]"=>"maximum length for tahun is 4",
                "required"=>"please input the nama tahun terbit"
            ],
            "stok"=>[
                "is_natural"=>"only integer input equals 0",
                "min_length[1]"=>"minimum length of stok is 1",
                "max_length[5000]"=>"maximum length for stok is 5000",
                "required"=>"please input the stok"
            ],
            "dipinjam"=>[
                "is_natural"=>"only integer input equals 0",
                "min_length[1]"=>"minimum length of dipinjam is 1",
                "max_length[5000]"=>"maximum length for dipinjam is 5000",
            ],
            "dibooking"=>[
                "is_natural"=>"only integer input equals 0",
                "min_length[1]"=>"minimum length of dibooking is 1",
                "max_length[5000]"=>"maximum length for dibooking is 5000",
            ],
            "gambar"=>[
                "min_length[5]"=>"minimum length of gambar is 5",
                "max_length[60]"=>"maximum length for gambar is 60",
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
     * menambahkan data ke table buku dengan parameter buku Entity object
     *
     * method ini mencoba inserting data ke table buku, method ini dapat menakibatkan
     * ValidationError exception. Serta method ini menangani databaseException jika
     * ada ketidaksesuaian dengan  foreign key
     *
     * @param BukuEntity $bukuEntity
     * @return void
     * @throws ValidationErrorMessages ketidaksesuaian input dengan validasi field
     * yang ada di table buku, cek bukuModel.
     */
    public function insertData(BukuEntity $bukuEntity): void
    {
        try {
            // menambahkan data
            $resultQuery = $this->insert($bukuEntity);

            // mengecek hasil dari query insert
            if($resultQuery === false)
            {
                $this->logger->debug("----------> masalah pada insert validasi exception <------------");

                // throw exception jika ada validasi error
                throw new ValidationErrorMessages(
                    "validation message exception",
                    $this->errors()
                );

            }else{
                $this->logger->info("---------> success insert data <----------".$bukuEntity);
            }

        }// catch error insert
        catch (\ReflectionException $e )
        {
            $this->logger->error("--------> error on insert method <--------");
            $this->logger->error($e->getMessage());

        }
        catch (DatabaseException $exception) {
            $this->logger->error("--------> error pada inserting data buku {$bukuEntity}  <-----------");
            $this->logger->error($exception->getMessage());
        }
    }


    /**
     * mencari data table buku dengan idBuku, kasus ketika user klik detail pada buku
     *
     * method ini mencoba mencari data buku dari parameter idBuku, dan akan mengembalikan
     * array object yang bisa diakses datanya dengan attributes/datamap buku entity
     * ex : $result->idBuku or $result->id_buku
     * method ini dapat mengakibatkan exception DatabaseExceptionNotFound
     *
     * @param string $idBuku
     * @return array|object
     * @throws DatabaseExceptionNotFound data buku dengan param tidak ditemukan
     */
    public function findBukuByIdBuku(string $idBuku): array|object
    {
        // mendapatkan data dari database, Note : ini menghasilkan Array object
        $result = $this->where("id_buku", $idBuku)->find();

        if ($result != null) {
            $this->logger->debug("---------> data buku ditemukan dengan id buku {$idBuku} <--------");
            // mendapatkan object data dari array object
            return $result[0];

        } else {
            $this->logger->debug("--------------> prolem findbukuByidBuku, data buku tidak ditemukan {$idBuku}<-----------");

            throw new DatabaseExceptionNotFound
            (
                "buku tidak ditemukan",
                ResponseInterface::HTTP_NOT_FOUND,
                $idBuku
            );
        }
    }

    /**
     * mendapatkan semua data pada table buku dengan limit data 10
     *
     * method ini mencoba mendapatkan semua data yang ada pada table buku, dengan limit 10 data
     * dan mengembalikan array kosong ketika data di table kosong
     *
     * @return array array of object
     */
    public function getAllDataBuku(){

        $queryResult = $this->findAll(10);

        // check data array, if the array is empty return empty of array
        if(count($queryResult)<1)
        {
            $this->logger->debug("---------> problem getalldatabuku data di table buku kosong <-------");
            return [];
        }

        $this->logger->debug("------------> getalldatabuku data buku ditemukan <-------------");
        return $queryResult;
    }


    /**
     * method ini digunakan untuk update data buku variable/field dibooking
     * NOTE : ini belum sempurna, karena masih harus mencari data buku lalu diambil nilai 'dibooking'
     *        yang selanjutnya baru query set update. Dikarenakan jika menggunakan
     *        set('dibooking','dibooking+1') tidak berefek
     * @param string $idBuku
     * @return void
     */
    public function updateIncrementDataDiBookingFieldByIdBuku(string $idBuku): void
    {
        try {
            // mencari data buku
            $queryResult    = $this->select('dibooking')->where('id_buku',$idBuku)->find();
            $dibookingValue = $queryResult[0]->dibooking;

            // update nilai dibooking
            $this->set('dibooking',$dibookingValue+1)
                ->where('id_buku',$idBuku)
                ->update();

            $this->logger->debug("---------> increment dibooking field berhasil id buku {$idBuku} <---------");

        } catch (\ReflectionException|DatabaseException $e) {
            $this->logger->error("----- kesalahan pada update data booking----");
            $this->logger->error($e->getMessage());
        }
    }

    /**
     * method ini digunakan untuk update data buku variable/field dipinjam
     * NOTE : ini belum sempurna, karena masih harus mencari data buku lalu diambil nilai 'dibooking'
     *        yang selanjutnya baru query set update. Dikarenakan jika menggunakan
     *        set('dibooking','dibooking+1') tidak berefek
     * @param string $idBuku
     * @return void
     */
    public function updateIncrementDataDiPinjamFieldByIdBuku(string $idBuku): void
    {
        try {
            // mencari data buku
            $queryResultDipinjam    = $this->select('dipinjam')->where('id_buku',$idBuku)->find();
            $queryResultStok        = $this->select('stok')->where('id_buku',$idBuku)->find();

            // increment dipinjam dan mengubah stok(decrement)
            $dipinjamValue = $queryResultDipinjam[0]->dipinjam;
            $stokValue     = $queryResultStok[0]->stok;

            // update nilai dipinjam
            $this->set('dipinjam',$dipinjamValue+1)
                ->where('id_buku',$idBuku)
                ->update();

            $this->set('stok',$stokValue-1)
                ->where('id_buku',$idBuku)
                ->update();

            $this->logger->debug("---------> increment dipinjam field berhasil id buku {$idBuku} <---------");

        } catch (\ReflectionException|DatabaseException $e) {
            $this->logger->error("----- kesalahan pada update data dipinjam----");
            $this->logger->error($e->getMessage());
        }
    }

    /**
     * method ini digunakan untuk update data buku variable/field dibooking
     * NOTE : ini belum sempurna, karena masih harus mencari data buku lalu diambil nilai 'dibooking'
     *        yang selanjutnya baru query set update. Dikarenakan jika menggunakan
     *        set('dibooking','dibooking+1') tidak berefek
     * @param string $idBuku
     * @return void
     */
    public function updateDecrementDataDiBookingFieldByIdBuku(string $idBuku): void
    {
        try {
            // mencari data buku
            $queryResult    = $this->select('dibooking')->where('id_buku',$idBuku)->find();
            $dibookingValue = $queryResult[0]->dibooking;

            // update nilai dibooking
            $this->set('dibooking',$dibookingValue-1)
                ->where('id_buku',$idBuku)
                ->update();

            $this->logger->debug("---------> decrement dibooking field berhasil id buku {$idBuku} <---------");

        } catch (\ReflectionException|DatabaseException $e) {
            $this->logger->error("----- kesalahan pada update data booking----");
            $this->logger->error($e->getMessage());
        }
    }
}
