<?php

namespace App\Entities;

use App\Libraries\LoggerCreations;
use CodeIgniter\Entity\Entity;
use Monolog\Logger;

class TempEntity extends Entity
{
    protected $attributes=
        [
          "tgl_booking"=>null,
          "id_user"=>null,
          "id_buku"=>null
        ];
    protected $datamap =
        [
            "idTemp"=>"id_temp",
            "tglBooking"=>"tgl_booking",
            "idUser"=>"id_user",
            "idBuku"=>"id_buku"
        ];
    private Logger $logger;

    /**
     * creating object TempObject, and return the object of tempObject
     * tanggal input is autofill on this method
     * @param string $idUser
     * @param string $idBuku
     * @return $this
     */
    public function createObject(string $idUser, string $idBuku)
    {
        $this->logger    = LoggerCreations::LoggerCreations(TempEntity::class);

        $this->attributes["tgl_booking"]    =   $this->setTanggalInput();
        $this->attributes["id_user"]        =   $idUser;
        $this->attributes["id_buku"]        =   $idBuku;

        return $this;
    }

    private function setTanggalInput(): ?string
    {
        try {
            $date = new \DateTime('now', new \DateTimeZone('Asia/Jakarta'));
            return $date->format('Y-m-d');

        } catch (\Exception $e) {
            $this->logger->error("error when setting date time on setTanggalInput");
            $this->logger->error($e->getTraceAsString());
            return null;
        }
    }

    public function __toString(): string
    {
        return <<<EOT
        Tgl booking: {$this->tgl_booking}
        id user: {$this->id_user}
        Id buku: {$this->id_buku}
        EOT;
    }
}
