<?php

namespace App\Models;

use App\Entities\BukuEntity;
use App\Exception\DatabaseExceptionNotFound;
use App\Exception\DatabaseFailedInsert;
use App\Libraries\LoggerCreations;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Database\Exceptions\DatabaseException;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Model;
use Monolog\Logger;

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
     * menambahkan data ke database
     * @param BukuEntity $bukuEntity object bukuEntity
     * @return void
     * @throws DatabaseFailedInsert insert failed data available on database
     */
    public function insertData(BukuEntity $bukuEntity): void
    {
        try {
            // menambahkan data
            $this->insert($bukuEntity);
            $this->logger->info("success insert data ".$bukuEntity);

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
                $bukuEntity);
        }
    }


    /**
     * mencari semua data buku dengan judul buku
     * @param string $judulBuku
     * @return array|object
     * @throws DatabaseExceptionNotFound data not exist
     */
    public function findAllBukuByJudulBuku(string $judulBuku): array|object
    {
        // mendapatkan data dari database, Note : ini menghasilkan Array object
        $result = $this->asArray()->where("judul_buku", $judulBuku)->find();

        if ($result != null) {
            // mendapatkan object data dari array object
            return $result;
        } else {
            throw new DatabaseExceptionNotFound
            (
                "user tidak ditemukan",
                ResponseInterface::HTTP_NOT_FOUND,
                $judulBuku
            );
        }
    }
}
