<?php

namespace App\Entities;

use App\Entities\Enum\StatusPinjam;
use App\Libraries\RandomString;
use CodeIgniter\Entity\Entity;

class PinjamEntity extends Entity
{
    protected $attributes=
        [
            "no_pinjam"=>null,
            "tgl_pinjam"=>null,
            "id_booking"=>null,
            "id_user"=>null,
            "tgl_kembali"=>null,
            "tgl_pengembalian"=>null,
            "status"=>null,
            "total_denda"=>null,
        ];
    protected $datamap =
        [
            "noPinjam"=>"no_pinjam",
            "idUser"=>"id_user",
            "idBooking"=>"id_booking",
            "tglPinjam"=>"tgl_pinjam",
            "tglKembali"=>"tgl_kembali",
            "tglPengembalian"=>"tgl_pengembalian",
            "totalDenda"=>"total_denda"
        ];


    /**
     * method pembuatan object Kategori BukuModel entity
     * @param string $tglPinjam
     * @param string $tglKembali
     * @param string $tglPengembalian
     * @param float $totalDenda
     * @param string $idUser
     * @param string $idBooking
     * @return $this object kategori userDashboard entity
     */
    public function createObject
    (
        string $tglKembali,
        string $idUser,string $idBooking,
        string $tglPengembalian=null,float $totalDenda=0
    ): static
    {
        $this->attributes["no_pinjam"]          = RandomString::random_string(15);
        $this->attributes["id_user"]            = $idUser;
        $this->attributes["id_booking"]         = $idBooking;
        $this->attributes["tgl_pinjam"]         = $this->setTanggalInput();
        $this->attributes["tgl_kembali"]        = $tglKembali;
        $this->attributes["tgl_pengembalian"]   = $tglPengembalian;
        $this->attributes["total_denda"]        = $totalDenda;
        $this->attributes["status"]             = StatusPinjam::DIPINJAM->value;

        return $this;
    }

    /**
     * method pembuatan tanggal input
     * @return string|null
     */
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
        no pinjam: {$this->noPinjam}
        id user: {$this->idUser}
        id booking: {$this->idBooking}
        tgl pinjam: {$this->tglPinjam}
        tgl kembali: {$this->tglKembali}
        tgl pengembalian: {$this->tglPengembalian}
        total denda: {$this->totalDenda}
        status :{$this->status}
        EOT;
    }
}
