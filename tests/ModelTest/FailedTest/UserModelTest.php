<?php

namespace ModelTest\FailedTest;

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
     * it will failed when the email was register on database
     * @return void
     * @test
     */
    public function testInsertDataFailed(){
        $this->userEntity->createObject
        (
            "dummy object","yummuy",
            "yummy@gmail.com","yum.jpg",
            "dummmyaja",RoleUser::REGULAR_USER
        );
        $this->expectException(DatabaseException::class);
        $this->userModel->insertData($this->userEntity);
    }
}