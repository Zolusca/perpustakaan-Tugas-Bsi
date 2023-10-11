<?php

namespace App\Entities;

use App\Libraries\RandomString;
use CodeIgniter\Entity\Entity;

class BukuEntity extends Entity
{
    protected $attributes=
        [
            "id_buku"=>null,
            "judul_buku"=>null,
            "id_kategori"=>null,
            "pengarang"=>null,
            "penerbit"=>null,
            "tahun_terbit"=>null,
            "isbn"=>null,
            "stok"=>null,
            "dipinjam"=>null,
            "dibooking"=>null,
            "gambar"=>null
        ];
    protected $datamap =
        [
            "idBuku"=>"id_buku",
            "judulBuku"=>"judul_buku",
            "idKategori"=>"id_kategori",
            "pengarang"=>"pengarang",
            "penerbit"=>"penerbit",
            "tahunTerbit"=>"tahun_terbit",
            "isbn"=>"isbn",
            "stok"=>"stok",
            "dipinjam"=>"dipinjam",
            "dibooking"=>"dibooking",
            "gambar"=>"gambar"
        ];

    /**
     * @param string $judulBuku
     * @param string $idKategoriBukuEntity
     * @param string $pengarang
     * @param string $penerbit
     * @param int $tahunTerbit
     * @param int $stok
     * @param string $gambar
     * @param int $dipinjam
     * @param int $dibooking
     * @return $this
     */
    public function createObject
    (
        string $judulBuku, string $idKategoriBukuEntity, string $pengarang,
        string $penerbit, int $tahunTerbit, int $stok,
        string $gambar, int $dipinjam=0, int $dibooking=0
    ): static
    {
        $this->attributes["id_buku"]        = RandomString::random_string(15);
        $this->attributes["isbn"]           = RandomString::random_string(9)."isbn";
        $this->attributes["judul_buku"]     = $judulBuku;
        $this->attributes["id_kategori"]    = $idKategoriBukuEntity;
        $this->attributes["pengarang"]      = $pengarang;
        $this->attributes["penerbit"]       = $penerbit;
        $this->attributes["tahun_terbit"]   = $tahunTerbit;
        $this->attributes["stok"]           = $stok;
        $this->attributes["gambar"]         = $gambar;
        $this->attributes["dipinjam"]       = $dipinjam;
        $this->attributes["dibooking"]      = $dibooking;

        return $this;
    }

    public function __toString(): string
    {
        return <<<EOT
        id_buku : {$this->id_buku}
        judul_buku : {$this->judul_buku}
        id_kategori : {$this->id_kategori}
        pengarang: {$this->pengarang}
        penerbit: {$this->penerbit}
        tahun_terbit: {$this->tahun_terbit}
        isbn: {$this->isbn}
        stok: {$this->stok}
        dipinjam: {$this->dipinjam}
        dibooking: {$this->dibooking}
        gambar: {$this->gambar}
        EOT;
    }
}
