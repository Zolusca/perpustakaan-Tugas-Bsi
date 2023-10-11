<?php

namespace EntityTest;

use App\Entities\Enum\RoleUser;
use App\Entities\UserEntity;
use CodeIgniter\Test\CIUnitTestCase;
use PHPUnit\Framework\Assert;

class UserEntityTest extends CIUnitTestCase
{
    /**
     * @test
     */
    public function createObjectUserEntity(){
        $objectUserEntity = new UserEntity();
        $objectUserEntity->createObject
        (
            "Haslam","jl. apa aja",
            "email@gmail.com","gambar.jpg",
            "haslamaja",RoleUser::ADMIN_USER
        );
        Assert::assertInstanceOf(UserEntity::class,$objectUserEntity);
    }
}