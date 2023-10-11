<?php

namespace App\Entities\Enum;

enum UserStatus :string
{
    case ACTIVE="active";
    case INACTIVE="inactive";
}