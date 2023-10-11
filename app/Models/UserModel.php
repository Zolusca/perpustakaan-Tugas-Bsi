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
     * menambahkan data ke database
     * @param UserEntity $userEntity
     * @return void
     * @throws DatabaseFailedInsert  insert failed data available on database
     * @throws ValidationErrorMessages error vaidation user model
     */
    public function insertData(UserEntity $userEntity): void
    {
        try {

            // menambahkan data
            $this->insert($userEntity);

            if(count($this->errors())>1){
                throw new ValidationErrorMessages(
                    "validation message",
                    $this->errors()
                );
            }
            else{
                $this->logger->info("success insert data " . $userEntity);
            }

        }// catch error insert
        catch (\ReflectionException $e) {
            $this->logger->error("--------error on insert method--------");
            $this->logger->error($e->getMessage());

        }//catch data duplicate
        catch (DatabaseException $exception) {
            throw new DatabaseFailedInsert(
                "Failed Insert, user already exist",
                ResponseInterface::HTTP_UNPROCESSABLE_ENTITY,
                $userEntity);
        }

    }


    /**
     * mencari data user dengan email
     * @param string $email
     * @return array|object
     */
    public function getUserByEmail(string $email): array|object
    {
        // mendapatkan data dari database, Note : ini menghasilkan Array object
        $result = $this->where("email",$email)->find();

        if($result != null){
            // mendapatkan object data dari array object
            return $result[0];
        }else{
            throw new DatabaseExceptionNotFound
            (
                "user tidak ditemukan",
                 ResponseInterface::HTTP_NOT_FOUND,
                $email
            );
        }
    }



}