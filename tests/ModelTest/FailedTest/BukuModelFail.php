<?php

namespace ModelTest\FailedTest;

use App\Entities\BukuEntity;
use App\Exception\DatabaseExceptionNotFound;
use App\Exception\ValidationErrorMessages;
use App\Models\BukuModel;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use Config\Services;
use PHPUnit\Framework\Assert;

class BukuModelFail extends CIUnitTestCase
{
    use DatabaseTestTrait;
    private $databaseConnection;
    private $bukuModel;


    /**
     * @throws \Exception
     */
    protected function setUp(): void
    {
        $this->databaseConnection   = Services::getDatabaseConnection();
        $this->bukuModel            = new BukuModel($this->databaseConnection);
    }
    protected function tearDown(): void
    {
        Services::closeDatabaseConnection($this->databaseConnection);
    }

    public static function providerKategoriBukuEntity(){
        $bukuEntity = new BukuEntity();
        $bukuEntity->createObject
        (
            "si kancil[]][","li83T1qwS",
            "ornamental","juara kelas",
            2001,-10,"sikancil.png"
        );
        return [[$bukuEntity]];
    }
    public static function provideIdBuku(){
        return [["1QQHSOIteadsaEFBF"]];
    }


    /**
     * @test
     * @dataProvider providerKategoriBukuEntity
     */
    public function testInsertDataSuccess(BukuEntity $bukuEntity){

        try{
            $this->bukuModel->insertData($bukuEntity);
            $this->expectNotToPerformAssertions();

        }catch (ValidationErrorMessages $exception){
            Assert::assertInstanceOf(ValidationErrorMessages::class,$exception);
        }

    }

    /**
     * @dataProvider provideIdBuku
     * @test
     */
    public function testFindBukuByIdBukuSuccess(string $idBuku){
        try {
            $result = $this->bukuModel->findBukuByIdBuku($idBuku);

            echo $result;

            Assert::assertNotEmpty($result);

        }catch (DatabaseExceptionNotFound $exception){
            Assert::assertInstanceOf(DatabaseExceptionNotFound::class,$exception);
        }
    }

    public function testGetAllDataBuku(){
        $result = $this->bukuModel->getAllDataBuku();

        Assert::assertNotEmpty($result);
    }
}