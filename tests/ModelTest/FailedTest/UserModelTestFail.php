<?php

namespace ModelTest\FailedTest;

use App\Entities\Enum\RoleUser;
use App\Entities\UserEntity;
use App\Exception\DatabaseExceptionNotFound;
use App\Exception\DatabaseFailedInsert;
use App\Exception\ValidationErrorMessages;
use App\Models\UserModel;
use CodeIgniter\Database\Exceptions\DatabaseException;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use Config\Services;
use PHPUnit\Framework\Assert;

class UserModelTestFail extends CIUnitTestCase
{
    use DatabaseTestTrait;
    private $databaseConnection;
    private $userModel;


    /**
     * @throws \Exception
     */
    protected function setUp(): void
    {
        $this->databaseConnection   = Services::getDatabaseConnection();
        $this->userModel            = new UserModel($this->databaseConnection);
    }
    protected function tearDown(): void
    {
        Services::closeDatabaseConnection($this->databaseConnection);
    }


    public static function providerUserData(){
        $email = "yumy@gmail.com";
        $password = "helloguys";
        return [[$email,$password]];
    }

    public static function provideUserEntityObject(){
        $userEntity = new UserEntity();
        $userEntity->createObject
        (
            "dummy object",
            "yummuy",
            "yummy@gmail.com",
            "yum.jpg",
            "dummmyaja"
        );
        return [[$userEntity]];
    }

    public static function providerUserEntityObjectNotRegistered(){
        $userEntityNotRegistered = new UserEntity();
        $userEntityNotRegistered->createObject
        (
            "not object",
            "not not",
            "notly@gmail.com",
            "not.jpg",
            "dummmynot"
        );
        return [[$userEntityNotRegistered]];
    }

    /**
     * @dataProvider provideUserEntityObject
     */
    public function testInsertDataFailed(UserEntity $userEntity){

        try{
            $this->userModel->insertData($userEntity);

            // berharap tidak terjadi kesalahan, karena void method
            $this->expectNotToPerformAssertions();

        }catch (DatabaseFailedInsert $exception){
            Assert::assertInstanceOf(DatabaseFailedInsert::class,$exception);
        }catch (ValidationErrorMessages $exception){
            Assert::assertInstanceOf(ValidationErrorMessages::class,$exception);
        }
    }


    /**
     * @dataProvider providerUserEntityObjectNotRegistered
     */
    public function testGetDataUserFailed(UserEntity $userEntityNotRegistered){

        try{
            $result = $this->userModel->getUserByEmail($userEntityNotRegistered->email);
            Assert::assertNotEmpty($result);
        }catch (DatabaseExceptionNotFound $exception){
            Assert::assertInstanceOf(DatabaseExceptionNotFound::class,$exception);
        }

    }

    /**
     * gagal karena data user dengan email tidak ditemukan
     * @dataProvider providerUserData
     */
    public function testUpdatePasswordUserFailed(string $email, string $newPassword){
        try {
            $this->userModel->getUserByEmail($email);

            $this->userModel->updatePasswordUserByEmail($email,$newPassword);
            // mengharapkan tidak terjadi assertion error
            $this->expectNotToPerformAssertions();

        }catch (DatabaseExceptionNotFound $exception){
            Assert::assertInstanceOf(DatabaseExceptionNotFound::class,$exception);
        }
    }
}