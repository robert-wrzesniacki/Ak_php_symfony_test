<?php

namespace App\Core\User\Infrastructure\Persistance;

use App\Core\User\Domain\Exception\UserNotFoundException;
use App\Core\User\Domain\Exception\UserNotActiveException;
use App\Core\User\Domain\Repository\UserRepositoryInterface;
use App\Core\User\Domain\User;
use App\Core\User\Domain\Status\UserStatus;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;

class DoctrineUserRepository implements UserRepositoryInterface
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    /**
     * @throws NonUniqueResultException
     */
    public function getByEmail(string $email): User
    {
        $user = $this->entityManager->createQueryBuilder()
            ->select('u')
            ->from(User::class, 'u')
            ->where('u.email = :user_email')
            ->setParameter(':user_email', $email)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();

        if (null === $user) {
            throw new UserNotFoundException('Użytkownik nie istnieje');
        }

        return $user;
    }

    public function getByEmailAndStatus(string $email, UserStatus $userStatus): User
    {
        $user = $this->entityManager->createQueryBuilder()
            ->select('u')
            ->from(User::class, 'u')
            ->where('u.email = :user_email')
            ->setParameter(':user_email', $email)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();

        if (null === $user) {
            throw new UserNotFoundException('Użytkownik nie istnieje');
        }

        if ($user->getStatus() !== $userStatus){
            throw new UserNotActiveException('Użytkownik nieaktywny');
        }

        return $user;
    }


    public function getUsersByStatus(UserStatus $userStatus): array
    {
        return $this->entityManager
            ->createQueryBuilder()
            ->select('u')
            ->from(User::class, 'u')
            ->where('u.status = :user_status')
            ->setParameter(':user_status', $userStatus)
            ->getQuery()
            ->getResult();
    }

    public function save(User $user): void
    {
        $this->entityManager->persist($user);

        $events = $user->pullEvents();
        foreach ($events as $event) {
            $this->eventDispatcher->dispatch($event);
        }
    }

    public function flush(): void
    {
        $this->entityManager->flush();
    }
}
