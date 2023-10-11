<?php

namespace App\Entities\Enum;

enum RoleUser :string
{
    case ADMIN_USER="admin";
    case REGULAR_USER="anggota";
}