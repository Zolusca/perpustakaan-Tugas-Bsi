<?php

namespace App\Models;

use App\Entities\BookingDetailEntity;
use CodeIgniter\Test\DatabaseTestTrait;
use Config\Services;
use PHPUnit\Framework\TestCase;

class BookingDetailModelTest extends TestCase
{
    use DatabaseTestTrait;
    private $databaseConnection;
    private $bookingDetailModel;
    private $bookingDetailEntity;

    /**
     * @throws \Exception
     */
    protected function setUp(): void
    {
        $this->databaseConnection      = Services::getDatabaseConnection();
        $this->bookingDetailModel            = new BookingDetailModel($this->databaseConnection);
        $this->bookingDetailEntity           = new BookingDetailEntity();
    }
    protected function tearDown(): void
    {
        Services::closeDatabaseConnection($this->databaseConnection);
    }

    public static function providerIdBookingIdBuku(){
        return [
            ["8ktFgijsj6rmfY9","ljJWX3wrLiE0aCa"]
        ];
    }
    /**
     *  the param provide data idbooking and idbuku, with actual sample data from database.
     *  so we dont need to configure booking model and buku model to perform get data and sent to booking detail entity object
     * @return void
     * @test
     * @dataProvider providerIdBookingIdBuku
     */
    public function testInsertDataSuccess($idBooking,$idBuku)
    {
        $this->bookingDetailEntity->createObject
        (
            $idBooking,
            $idBuku
        );
        $this->expectNotToPerformAssertions();
        $this->bookingDetailModel->insertData($this->bookingDetailEntity);
    }
}
