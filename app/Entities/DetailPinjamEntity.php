<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class DetailPinjamEntity extends Entity
{
    protected $attributes=
        [
            "no_pinjam"=>null,
            "id_buku"=>null,
            "denda"=>null
        ];
    protected $datamap =
        [
            "noPinjam"=>"no_pinjam",
            "idBuku"=>"id_buku",
            "denda"=>"denda"
        ];

    public function createObject(string $noPinjam,string $idBuku,float $denda){
        $this->attributes['no_pinjam']  = $noPinjam;
        $this->attributes['id_buku']    = $idBuku;
        $this->attributes['denda']      = $denda;

        return $this;
    }

    public function __toString(): string
    {
        return <<<EOT
        n pinjam: {$this->no_pinjam}
        id buku: {$this->id_buku}
        denda: {$this->denda}
        EOT;
    }
}