<?php

namespace App\Entities;

use App\Libraries\LoggerCreations;
use App\Libraries\RandomString;
use CodeIgniter\Entity\Entity;
use Monolog\Logger;

class BookingEntity extends Entity
{
    protected $attributes=
        [
            "id_booking"=>null,
            "tgl_booking"=>null,
            "batas_ambil"=>null,
            "id_user"=>null
        ];
    protected $datamap =
        [
            "idBooking"=>"id_booking",
            "tglBooking"=>"tgl_booking",
            "batasAmbil"=>"batas_ambil",
            "idUser"=>"id_user"
        ];
    private Logger $logger;


    ////////////////////////// METHOD /////////////////////////////////////

    /**
     * method untuk membuat object BookingEntity, id booking dibuat otomatis
     * @param string $tglBooking
     * @param string $idUser from UserEntity
     * @return $this Object User Entity
     */
    public function createObject(string $tglBooking, string $idUser): static
    {
        $this->logger    = LoggerCreations::LoggerCreations(BookingEntity::class);
        $tanggal_hari_ini = date('Y-m-d');
        $tanggal_ambil   = date('Y-m-d', strtotime($tanggal_hari_ini . ' +5 days'));

        $this->attributes["id_booking"]     = RandomString::random_string(15);
        $this->attributes["tgl_booking"]    = $this->setTanggalInput($tglBooking);
        $this->attributes["batas_ambil"]    = $this->setTanggalInput($tanggal_ambil);
        $this->attributes["id_user"]        = $idUser;

        return $this;
    }

    private function setTanggalInput(string $datetime): ?string
    {
        try {
            $date = new \DateTime($datetime, new \DateTimeZone('Asia/Jakarta'));
            return $date->format('Y-m-d');

        } catch (\Exception $e) {
            $this->logger->error("error when setting date time on setTanggalInput");
            $this->logger->error($e->getMessage());
            return null;
        }
    }

    public function __toString(): string
    {
        return <<<EOT
        ID booking: {$this->id_booking}
        Tgl booking: {$this->tgl_booking}
        Batas Ambil: {$this->batas_ambil}
        Id user: {$this->id_user}
        EOT;
    }
}
