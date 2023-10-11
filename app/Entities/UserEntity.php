<?php

namespace App\Entities;

use App\Entities\Enum\RoleUser;
use App\Entities\Enum\UserStatus;
use App\Libraries\LoggerCreations;
use App\Libraries\RandomString;
use CodeIgniter\Entity\Entity;
use Monolog\Logger;

class UserEntity extends Entity
{
    protected $attributes=
        [
            "id_user"=>null,
            "role_user"=>null,
            "nama"=>null,
            "alamat"=>null,
            "email"=>null,
            "gambar"=>null,
            "password"=>null,
            "is_active"=>null,
            "tanggal_input"=>null
        ];
    protected $datamap =
        [
            "idUser"=>"id_user",
            "roleUser"=>"role_user",
            "userStatus"=>"user_status",
            "tanggalInput"=>"tanggal_input"
        ];
    protected $dates   = ['created_at', 'updated_at', 'deleted_at'];
    private Logger $logger;

    ////////////////////////// METHOD /////////////////////////////////////


    /**
     * method untuk membuat object User Entity
     * @param string $nama
     * @param string $alamat
     * @param string $email
     * @param string $gambar
     * @param string $password
     * @return $this Object User Entity
     */
    public function createObject(string $nama, string $alamat, string $email,
                                 string $gambar, string $password): static
    {
        $this->logger    = LoggerCreations::LoggerCreations(UserEntity::class);

        $this->attributes["id_user"]    = RandomString::random_string(15);
        $this->attributes["is_active"]  = UserStatus::ACTIVE->value;
        $this->attributes["tanggal_input"]= $this->setTanggalInput();
        $this->attributes["nama"]       = $nama;
        $this->attributes["alamat"]     = $alamat;
        $this->attributes["email"]      = $email;
        $this->attributes["gambar"]     = $gambar;
        $this->attributes["password"]   = $password;
        $this->attributes["role_user"]  = RoleUser::REGULAR_USER->value;

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
            return $date->format('Y-m-d H:i:s');

        } catch (\Exception $e) {
            $this->logger->error("error when setting date time on setTanggalInput");
            $this->logger->error($e->getTraceAsString());
            return null;
        }
    }

    public function __toString(): string
    {
        return <<<EOT
        ID User: {$this->id_user}
        Nama: {$this->nama}
        Alamat: {$this->alamat}
        Email: {$this->email}
        Gambar: {$this->gambar}
        Password: {$this->password}
        Role User: {$this->roleUser}
        User Status: {$this->userStatus}
        Tanggal Input: {$this->tanggalInput}
        EOT;
    }
}