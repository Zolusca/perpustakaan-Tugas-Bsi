<?php

namespace ModelTest\SuccessTest;

use App\Entities\KategoriBukuEntity;
use App\Exception\DatabaseExceptionNotFound;
use App\Exception\DatabaseFailedInsert;
use App\Models\KategoriBukuModel;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use Config\Services;
use PHPUnit\Framework\Assert;

class KategoriBukuModelTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    private $databaseConnection;
    private $kategoriBukuModel;

    /**
     * @throws \Exception
     */
    protected function setUp(): void
    {
        $this->databaseConnection   = Services::getDatabaseConnection();
        $this->kategoriBukuModel            = new KategoriBukuModel($this->databaseConnection);
    }
    protected function tearDown(): void
    {
        Services::closeDatabaseConnection($this->databaseConnection);
    }


    public static function providerKategoriBukuObject(){
        $kategoriBuku = new KategoriBukuEntity();
        $kategoriBuku->createObject("biografi");
        return[[$kategoriBuku]];
    }

    public static function providerIdKategori(){
        return [["JIcHFWk1N"]];
    }

    /**
     * @dataProvider providerKategoriBukuObject
     * @test
     */
    public function testInsertDataSuccess(KategoriBukuEntity $kategoriBukuEntity){
        try {

            $this->kategoriBukuModel->insertData($kategoriBukuEntity);
            $this->expectNotToPerformAssertions();

        }catch (DatabaseFailedInsert $exception){
            Assert::assertInstanceOf(DatabaseFailedInsert::class,$exception);
        }
    }

    /**
     * @dataProvider providerIdKategori
     * @test
     */
    public function testGetNamaKategoriByIdKategoriSuccess(string $idKategori){

        try {
            $result = $this->kategoriBukuModel->getNamaKategoriByIdKategori($idKategori);

            Assert::assertNotEmpty($result,"data tidak empty");

        }catch (DatabaseExceptionNotFound $exception){
            Assert::assertInstanceOf(DatabaseExceptionNotFound::class,$exception,"data tidak ditemukan");
        }
    }


    /**
     * @test
     */
    public function testGetAllNamaKategoriSuccess(){
        $result = $this->kategoriBukuModel->getAllNamaKategori();

        // menampilkan data
        foreach ($result as $value){
            echo $value->namaKategori." ".$value->idKategori."\n";
        }
        Assert::assertNotEmpty($result);
    }


}