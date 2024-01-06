<?php

namespace App\Core\User\Application\Query\GetUsersByStatus;

use App\Core\User\Application\UserList\UserList;
use App\Core\User\Domain\User;
use App\Core\User\Domain\Repository\UserRepositoryInterface;
use App\Core\User\Domain\Status\UserStatus;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class GetUsersByStatusHandler
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository
    ) {}

    public function __invoke(GetUsersByStatusQuery $query): array
    {
        $users = $this->userRepository->getUsersByStatus(
            $query->userStatus
        );

        return array_map(function (User $user) {
            return new UserList(
                $user->getId(),
                $user->getEmail(),
                $user->getStatus()
            );
        }, $users);
    }
}
