<?php

namespace EntityTest;

use App\Entities\Enum\RoleUser;
use App\Entities\KategoriBukuEntity;
use App\Entities\UserEntity;
use CodeIgniter\Test\CIUnitTestCase;
use PHPUnit\Framework\Assert;

class KategoriBukuEntityTest extends CIUnitTestCase
{
    /**
     * @test
     */
    public function createKategoriBukuEntity(){
        $objectKategoriBukuEntity = new KategoriBukuEntity();
        $objectKategoriBukuEntity->createObject
        (
            "pahlawan"
        );
        Assert::assertInstanceOf(KategoriBukuEntity::class,$objectKategoriBukuEntity);
    }
}