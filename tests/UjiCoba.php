<?php

namespace tests;

use CodeIgniter\Test\CIUnitTestCase;

class UjiCoba extends CIUnitTestCase
{

    public function testing()
    {
        $buku = new \App\Models\BukuModel(\Config\Services::getDatabaseConnection());
        $buku->updateIncrementDataDiBookingFieldByIdBuku("ljJWX3wrLiE0aCa");
        $this->expectNotToPerformAssertions();
    }

    public function testkok()
    {
        $entity = new \App\Entities\UserEntity();
        $model = new \App\Models\UserModel(\Config\Services::getDatabaseConnection());
        $entity->createObject("haslam", "jl oggng", "haslam@gmail.com", "uwu.jpg", "haslam");
        $model->insertData($entity);
    }
}