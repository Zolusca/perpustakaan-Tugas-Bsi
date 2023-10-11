<?php

namespace App\Entities;

use App\Libraries\RandomString;
use CodeIgniter\Entity\Entity;

class BookingDetailEntity extends Entity
{
    protected $attributes=
        [
            "id"=>null,
            "id_booking"=>null,
            "id_buku"=>null
        ];
    protected $datamap =
        [
            "idBookingDetail"=>"id",
            "idBooking"=>"id_booking",
            "idBuku"=>"id_buku"
        ];

    ////////////////////////// METHOD /////////////////////////////////////


    /**
     * method untuk membuat object Booking detail entity
     * @param string $idBooking
     * @param string $idBuku
     * @return $this object booking detail entity
     */
    public function createObject(string $idBooking, string $idBuku): static
    {
        $this->attributes["id"]         = RandomString::random_string(15);
        $this->attributes["id_booking"] = $idBooking;
        $this->attributes["id_buku"]    = $idBuku;

        return $this;
    }

    public function __toString(): string
    {
        return <<<EOT
        ID booking detail: {$this->id}
        id booking: {$this->id_booking}
        id buku: {$this->id_buku}
        EOT;
    }
}
