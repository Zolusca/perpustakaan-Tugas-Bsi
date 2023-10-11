<?php

namespace ModelTest\SuccessTest;

use App\Entities\KategoriBukuEntity;
use App\Models\KategoriBukuModel;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use Config\Services;

class KategoriBukuModelTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    private $databaseConnection;
    private $kategoriBukuModel;
    private $kategoriBukuEntity;

    /**
     * @throws \Exception
     */
    protected function setUp(): void
    {
        $this->databaseConnection   = Services::getDatabaseConnection();
        $this->kategoriBukuModel            = new KategoriBukuModel($this->databaseConnection);
        $this->kategoriBukuEntity           = new KategoriBukuEntity();
    }
    protected function tearDown(): void
    {
        Services::closeDatabaseConnection($this->databaseConnection);
    }

    /**
     * it will success when data not exist on database
     * @return void
     * @test
     */
    public function testInsertDataSuccess(){
        // pembuatan object kategori entity
        $this->kategoriBukuEntity->createObject
        (
          "komik"
        );
        // berharap tidak terjadi kesalahan
        $this->expectNotToPerformAssertions();
        // insert data dengan entity
        $this->kategoriBukuModel->insertData($this->kategoriBukuEntity);
    }

}