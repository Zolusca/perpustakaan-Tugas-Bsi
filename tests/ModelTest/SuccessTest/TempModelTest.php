<?php

namespace ModelTest\SuccessTest;

use App\Entities\KategoriBukuEntity;
use App\Entities\TempEntity;
use App\Exception\DatabaseExceptionNotFound;
use App\Exception\DatabaseFailedInsert;
use App\Models\KategoriBukuModel;
use App\Models\TempModel;
use App\Models\UserModel;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\Fabricator;
use CodeIgniter\Test\Interfaces\FabricatorModel;
use Config\Services;
use PHPUnit\Framework\MockObject\Exception;
use function PHPUnit\Framework\returnValue;

class TempModelTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    private $databaseConnection;
    private $tempModel;

    /**
     * @throws \Exception
     */
    protected function setUp(): void
    {
        $this->databaseConnection   = Services::getDatabaseConnection();
        $this->tempModel            = new TempModel($this->databaseConnection);
    }
    protected function tearDown(): void
    {
        Services::closeDatabaseConnection($this->databaseConnection);
    }

    public static function provideEntityObject(){
        $tempEntity = new TempEntity();
        $tempEntity->createObject("CtkW0CPg2Oh2JHp","tKix2SNJ2i0Qfts");
        return [
            [$tempEntity]
        ];
    }

    public static function provideIdTempIdUserIdBuku(){
        $data = [
            "idTemp"=>"26",
            "idUser"=>"CtkW0CPg2Oh2JHp",
            "idBuku"=>"1QQHSOItejaEFBF"
        ];
        return [[
            $data
        ]];
    }

    /**
     * @dataProvider provideEntityObject
     */
    public function testInsertDataSuccess(TempEntity $tempEntity){
        try {
            $this->tempModel->insertData($tempEntity);
            $this->expectNotToPerformAssertions();
        }catch (DatabaseFailedInsert $exception){
            $this->assertInstanceOf(DatabaseFailedInsert::class,$exception);
        }
    }


    /**
     * @dataProvider provideEntityObject
     */
    public function testFindAllDataTempByIdUserSuccess(TempEntity $tempEntity){
        try{
            $result = $this->tempModel->findAllDataTempUserByIdUser($tempEntity->id_user);

            foreach ($result as $data){
                echo $data->idTemp." ".$data->id_user." ".$data->id_buku;
            }

            $this->assertNotEmpty($result);

        }catch (DatabaseExceptionNotFound $exception){
            $this->assertInstanceOf(DatabaseExceptionNotFound::class,$exception);
        }
    }

    /**
     * @dataProvider provideEntityObject
     */
    public function testCountDataByIdUserSucces(TempEntity $tempEntity){
        $result = $this->tempModel->countDataTempByIdUser($tempEntity->id_user);
        echo "data di table temp ".$result;
        $this->expectNotToPerformAssertions();
    }


    /**
     * @dataProvider provideIdTempIdUserIdBuku
     */
    public function testDeleteDataByIdTempSucces(array $data){
        try {
            $this->tempModel->deleteDataByIdTemp($data["idTemp"]);
            $this->expectNotToPerformAssertions();
        }catch (DatabaseExceptionNotFound $exception){
            $this->assertInstanceOf(DatabaseExceptionNotFound::class,$exception);
        }
    }

    /**
     * @dataProvider provideIdTempIdUserIdBuku
     */
    public function testdeleteAllDataTempByIdUser(array $data){
        try {
            $this->tempModel->deleteAllDataTempByIdUser($data["idUser"]);
            $this->expectNotToPerformAssertions();

        }catch (DatabaseExceptionNotFound $exception){
            $this->assertInstanceOf(DatabaseExceptionNotFound::class,$exception);
        }
    }

    /**
     * @dataProvider provideIdTempIdUserIdBuku
     */
    public function testcheckDuplicateTempBooking(array $data){
            $result = $this->tempModel->checkDuplicateTempBooking($data["idUser"],$data["idBuku"]);
            $this->assertTrue($result);

    }
}