<?php

namespace App\Models;


use App\Entities\UserEntity;
use App\Exception\DatabaseExceptionNotFound;
use App\Exception\DatabaseFailedInsert;
use App\Exception\ValidationErrorMessages;
use App\Libraries\LoggerCreations;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Database\Exceptions\DatabaseException;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Model;
use Monolog\Logger;

class UserModel extends Model
{
    protected $table        = "user";
    protected $primaryKey   = "id_user";
    protected $useAutoIncrement = false;
    protected $returnType       = UserEntity::class;
    protected $useSoftDeletes   = false;
    protected $allowedFields    =
        [
            "id_user","role_user",
            "nama","alamat",
            "email","gambar",
            "password","is_active",
            "tanggal_input"
        ];


    // Validation
    protected $validationRules      = [
        "nama"=>"alpha_numeric_space|min_length[5]|max_length[100]|required",
        "alamat"=>"min_length[5]|required",
        "email"=>"valid_email|required",
        "gambar"=>"min_length[5]|max_length[60]",
        "password"=>"alpha_numeric|min_length[6]|max_length[50]|required"
    ];
    protected $validationMessages   = [
        "nama"=>
            [
                "alpha_numeric_space"=>"only alphabet, numeric and space is allowed for name",
                "min_length[5]"=>"minimum length of name is 5",
                "max_length[100]"=>"maximum length for name is 100",
                "required"=>"please input the name"
            ],
        "alamat"=>
            [
                "min_length[5]"=>"minimum length of alamat field is 5",
                "required"=>"please input the alamat"
            ],
        "email"=>
            [
                "valid_email"=>"please use a valid email, contain @",
                "required"=>"please input the email"
            ],
        "gambar"=>
            [
                "min_length[5]"=>"minimum length of gambar is 5",
                "max_length[60]"=>"maximum length for gambar is 60",
            ],
        "password"=>
            [
                "alpha_numeric"=>"only alphabet and numeric for password",
                "min_length[5]"=>"minimum length of password is 5",
                "max_length[50]"=>"maximum length for password is 50",
                "required"=>"please input the password"
            ]
    ];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;
    private Logger $logger;


    public function __construct(ConnectionInterface $db)
    {
        parent::__construct($db);

        $this->logger = LoggerCreations::LoggerCreations(UserModel::class);
    }


    /**
     * Insert data user ke table user dengan menggunakan UserEntity object
     *
     * method ini mencoba inserting data dan handle kesalahan validasi field data table
     * serta menangani data duplicate dari email yang sama
     * ---
     * buat object dari method UserEntity->createObject()
     * @param UserEntity $userEntity
     * @return void
     * @throws DatabaseFailedInsert -->gagal inserting data karena, duplicate entry email dari user--
     * @throws ValidationErrorMessages -->error karena data variable dari param UserEntity
     * tidak sesuai dengan aturan yang ada pada validation UserModel--
     */
    public function insertData(UserEntity $userEntity): void
    {
        try {

            // menambahkan data, dan menyimpan hasil dari aksi query insert
            $resultQuery = $this->insert($userEntity);

            // mengecek hasil dari query insert
            if($resultQuery === false)
            {
                // throw exception jika ada validasi error
                throw new ValidationErrorMessages(
                    "validation message exception",
                    $this->errors()
                );
            }
            else{
                $this->logger->info("-------> success insert data " . $userEntity);
            }

        }// catch error insert
        catch (\ReflectionException $e) {
            $this->logger->error("--------------> error insert data <------------");
            $this->logger->error($e->getMessage());

        }//catch data duplicate
        catch (DatabaseException $exception) {
            $this->logger->debug("-------------> duplicate entry data Inserting data {$userEntity} <------------");
            $this->logger->debug($exception->getMessage());

            throw new DatabaseFailedInsert(
                "Failed Insert, user already exist",
                ResponseInterface::HTTP_UNPROCESSABLE_ENTITY,
                $userEntity);
        }

    }


    /**
     * mencari data yang sesuai dengan parameter email user
     *
     * method ini akan mecari 1 data yang sesuai dari table user dengan email user, dan dapat
     * terjadi exception jika data dengan email tidak ditemukan
     *
     * @param string $email
     * @return array|object --> data pengembalian berbentuk 1 data array of object, anda bisa
     * mengakses value dennganattributes atau datamap pada UserEntity ex: $result->id_user
     * @throws DatabaseExceptionNotFound --> user dengan email tidak ditemukan di table
     */
    public function getUserByEmail(string $email): array|object
    {
        // mendapatkan data dari database, Note : ini menghasilkan Array object
        $result = $this->where("email",$email)->find();

        if($result != null){
            // mendapatkan object data dari array object
            return $result[0];
        }else{
            $this->logger->debug("-----------> problem getuserbyemail <---------");
            $this->logger->debug("-----------> data not found {$email} <---------");
            throw new DatabaseExceptionNotFound
            (
                "user tidak ditemukan",
                 ResponseInterface::HTTP_NOT_FOUND,
                $email
            );
        }
    }

    /**
     * mengupdate data field password 1 user pada database
     *
     * method ini akan mengupdate data password berdasarkan pencarian email
     * method ini menangkap databaseException apabila ada kesalahan param query seperti
     * 'emil' ->'email'
     *
     * Note : pastikan cek keberadaan/cari data user terlebih dahulu di database
     * karena method ini tidak menyediakan pengecekan data user di table
     *
     * @param string $email
     * @param string $newPassword
     * @return void
     */
    public function updatePasswordUserByEmail(string $email,string $newPassword): void
    {
        try {

            // update password user
            $this->set('password',$newPassword)
                ->where('email',$email)
                ->update();

        } catch (\ReflectionException $e) {
            $this->logger->error("--------> kesalahan pada updatePasswordUser <--------");
            $this->logger->error($e->getMessage());
        } catch (DatabaseException $exception){
            $this->logger->debug("-----------> kesalahan pada updatePasswordUser <---------");
            $this->logger->debug($exception->getMessage());
        }
    }

}