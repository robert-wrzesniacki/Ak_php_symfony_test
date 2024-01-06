<?php

namespace App\Core\User\Application\UserList;

use App\Core\User\Domain\Status\UserStatus;

class UserList
{
    public function __construct(
        public readonly int $id,
        public readonly string $email,
        public readonly UserStatus $status,
    ) {}
}
