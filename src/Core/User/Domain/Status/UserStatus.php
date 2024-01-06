<?php

namespace App\Core\User\Domain\Status;

enum UserStatus: string
{
    case ACTIVE = 'aktywny';
    case INACTIVE = 'nieaktywny';
}
