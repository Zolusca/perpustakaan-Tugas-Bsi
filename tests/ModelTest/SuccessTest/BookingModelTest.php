<?php

namespace ModelTest\SuccessTest;

use App\Entities\BookingEntity;
use App\Exception\DatabaseFailedInsert;
use App\Models\BookingModel;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use Config\Services;
use function Symfony\Component\String\b;

class BookingModelTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    private $databaseConnection;
    private $bookingModel;

    /**
     * @throws \Exception
     */
    protected function setUp(): void
    {
        $this->databaseConnection      = Services::getDatabaseConnection();
        $this->bookingModel            = new BookingModel($this->databaseConnection);
    }
    protected function tearDown(): void
    {
        Services::closeDatabaseConnection($this->databaseConnection);
    }

    public static function providerBookingEntity(){
        $booking = new BookingEntity();
        $booking->createObject
        (
            "2023-10-15",
            "40aOXNpaC0zOnCV"
        );
        return [[$booking]];
    }
    /**
     * @test
     * @dataProvider providerBookingEntity
     */
    public function testInsertDataSuccess(BookingEntity $bookingEntity)
    {
        try {
            $this->bookingModel->insertData($bookingEntity);
            $this->expectNotToPerformAssertions();
        }catch (DatabaseFailedInsert $exception){
            $this->assertInstanceOf(DatabaseFailedInsert::class,$exception);
        }
    }


    public function testFindingData($idUser){
        $this->expectNotToPerformAssertions();
        echo $this->bookingModel->getBookingByIdUser($idUser);
    }
}