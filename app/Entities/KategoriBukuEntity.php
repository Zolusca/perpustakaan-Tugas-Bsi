<?php

namespace App\Entities;

use App\Libraries\RandomString;
use CodeIgniter\Entity\Entity;

class KategoriBukuEntity extends Entity
{
    protected $attributes=
        [
            "id_kategori"=>null,
            "nama_kategori"=>null
        ];
    protected $datamap =
        [
            "idKategori"=>"id_kategori",
            "namaKategori"=>"nama_kategori"
        ];


    /**
     * method pembuatan object KategoriBukuEntity
     * @param string $namaKategori
     * @return $this object kategori buku entity
     */
    public function createObject(string $namaKategori): static
    {
            $this->attributes["id_kategori"] = RandomString::random_string(9);
            $this->attributes["nama_kategori"] = $namaKategori;

            return $this;
    }

    public function __toString(): string
    {
        return <<<EOT
        ID Kategori: {$this->id_kategori}
        Nama kategori: {$this->nama_kategori}
        EOT;
    }
}
