<?php

namespace App\Core\User\Domain\Repository;

use App\Core\User\Domain\Exception\UserNotFoundException;
use App\Core\User\Domain\Exception\UserNotActiveException;
use App\Core\User\Domain\User;
use App\Core\User\Domain\Status\UserStatus;

interface UserRepositoryInterface
{
    /**
     * @throws UserNotFoundException
     */
    public function getUsersByStatus(UserStatus $userStatus): array;
    public function getByEmail(string $email): User;

   /**
     * @throws UserNotFoundException
     * @throws UserNotActiveException
     */
    public function getByEmailAndStatus(string $email, UserStatus $userStatus): User;

    public function save(User $user): void;
    public function flush(): void;
}