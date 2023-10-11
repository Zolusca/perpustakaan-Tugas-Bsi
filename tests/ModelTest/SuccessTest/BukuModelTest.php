<?php

namespace ModelTest\SuccessTest;

use App\Entities\BukuEntity;
use App\Entities\KategoriBukuEntity;
use App\Models\BukuModel;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use Config\Services;

class BukuModelTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    private $databaseConnection;
    private $bukuModel;
    private $bukuEntity;

    /**
     * @throws \Exception
     */
    protected function setUp(): void
    {
        $this->databaseConnection   = Services::getDatabaseConnection();
        $this->bukuModel            = new BukuModel($this->databaseConnection);
        $this->bukuEntity           = new BukuEntity();
    }
    protected function tearDown(): void
    {
        Services::closeDatabaseConnection($this->databaseConnection);
    }

    public static function providerKategoriBukuEntity(){
        return [
            ["li83T1qwS","komik"]
        ];
    }
    /**
     * the param provide data kategoriBukuEntities, with actual sample data from database.
     * so we dont need to configure kategori model to perform get data and sent to buku entity object
     *
     * @test
     * @return void
     * @dataProvider providerKategoriBukuEntity
     */
    public function testInsertDataSuccess($idKategori,$namaKategori){
       // membuat buku entities
       $this->bukuEntity->createObject
       (
            "One Piece",$idKategori,"Echiro Oda",
           "Toei Studio",2001,100,"onePiece.jpg"
       );

       // berharap tidak ada exception atau error
       $this->expectNotToPerformAssertions();

       $this->bukuModel->insertData($this->bukuEntity);

    }
}