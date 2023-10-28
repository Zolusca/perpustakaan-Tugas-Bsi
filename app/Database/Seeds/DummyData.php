<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DummyData extends Seeder
{
    public function run()
    {
        $dataUserAdmin = [
                "id_user"=>" 40mOVNpsC2z1nCS",
                "nama"=>"zolusca",
                "alamat"=>"jl. perdamaian",
                "email"=>"zolusca@gmail.com",
                "gambar"=>"jadlO6X2K.png",
                "password"=>"zolusca",
                "role_user"=>"admin",
                "is_active"=>"active",
                "tanggal_input"=>date('y:m:d H:i:s')
        ];

        $dataUserAnggota = [
                "id_user"=>" 31mOVLpsC2z9mCS",
                "nama"=>"haslam",
                "alamat"=>"jl. perusakan",
                "email"=>"haslam@gmail.com",
                "gambar"=>"jadlO6X2K.png",
                "password"=>"haslam",
                "role_user"=>"anggota",
                "is_active"=>"active",
                "tanggal_input"=>date('y:m:d H:i:s')
        ];

        $dataKategoriBuku1=[
            "id_kategori"=>"kf91T3qoS",
            "nama_kategori"=>"komik"
        ];

        $dataKategoriBuku2=[
            "id_kategori"=>"la11R3coD",
            "nama_kategori"=>"biografi"
        ];

        $dataBuku1=[
            "id_buku"=>"xdXerV4ugXv5KKP",
            "judul"=>"sangkuriang",
            "id_kategori"=>"kf91T3qoS",
            "pengarang"=>"tuk dalang",
            "penerbit"=>"robinson",
            "tahun_terbit"=>"2001",
            "isbn"=>"z0I347sGGisbn",
            "stok"=>50,
            "dipinjam"=>0,
            "dibooking"=>0,
            "gambar"=>"dpq6KdhC9.jpg"
        ];

        $dataBuku2=[
            "id_buku"=>"lOO0rV4uvXv5KKP",
            "judul"=>"one piece",
            "id_kategori"=>"kf91T3qoS",
            "pengarang"=>"echiro oda",
            "penerbit"=>"toei studio",
            "tahun_terbit"=>"1995",
            "isbn"=>"x0I34c0GGisbn",
            "stok"=>30,
            "dipinjam"=>0,
            "dibooking"=>0,
            "gambar"=>"nYQWyMo2k.jpeg"
        ];

        $this->db->table('user')->insert($dataUserAdmin);
        $this->db->table('user')->insert($dataUserAnggota);
        $this->db->table('kategori_buku')->insert($dataKategoriBuku1);
        $this->db->table('kategori_buku')->insert($dataKategoriBuku2);
        $this->db->table('buku')->insert($dataBuku1);
        $this->db->table('buku')->insert($dataBuku2);
    }
}