<?php

namespace ModelTest\SuccessTest;

use App\Entities\BookingEntity;
use App\Models\BookingModel;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use Config\Services;

class BookingModelTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    private $databaseConnection;
    private $bookingModel;
    private $bookingEntity;

    /**
     * @throws \Exception
     */
    protected function setUp(): void
    {
        $this->databaseConnection      = Services::getDatabaseConnection();
        $this->bookingModel            = new BookingModel($this->databaseConnection);
        $this->bookingEntity           = new BookingEntity();
    }
    protected function tearDown(): void
    {
        Services::closeDatabaseConnection($this->databaseConnection);
    }

    public static function providerIdUserEntity(){
        return [
            ["jatHrrO9EI0O90x"]
        ];
    }
    /**
     *  the param provide data idUser, with actual sample data from database.
     *  so we dont need to configure user model to perform get data and sent to booking entity object
     * @return void
     * @test
     * @dataProvider providerIdUserEntity
     */
    public function testInsertDataSuccess($idUser)
    {
        $this->bookingEntity->createObject
        (
            "2000-10-20",
            "2001-10-20",
            $idUser
        );
        $this->expectNotToPerformAssertions();
        $this->bookingModel->insertData($this->bookingEntity);
    }
}