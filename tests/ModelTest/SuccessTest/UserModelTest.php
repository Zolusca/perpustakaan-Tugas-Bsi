<?php

namespace ModelTest\SuccessTest;

use App\Entities\Enum\RoleUser;
use App\Entities\UserEntity;
use App\Models\UserModel;
use CodeIgniter\Database\Exceptions\DatabaseException;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use Config\Services;

class UserModelTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    private $databaseConnection;
    private $userModel;
    private $userEntity;

    /**
     * @throws \Exception
     */
    protected function setUp(): void
    {
        $this->databaseConnection   = Services::getDatabaseConnection();
        $this->userModel            = new UserModel($this->databaseConnection);
        $this->userEntity           = new UserEntity();
    }
    protected function tearDown(): void
    {
        Services::closeDatabaseConnection($this->databaseConnection);
    }


    /**
     * it will success when data not exist on database
     * @return void
     */
    public function testInsertDataSuccess(){
        // pembuatan object ke User entity
        $this->userEntity->createObject
        (
          "dummy object","yummuy",
            "yummy@gmail.com","yum.jpg",
            "dummmyaja"
        );
        // berharap tidak terjadi kesalahan
        $this->expectNotToPerformAssertions();
        // insert data dengan entity
        $this->userModel->insertData($this->userEntity);
    }



}