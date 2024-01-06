<?php

namespace App\Core\User\Application\Command\CreateUser;

use App\Core\User\Domain\User;
use App\Core\User\Domain\Repository\UserRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use App\Common\Mailer\MailerInterface;

#[AsMessageHandler]
class CreateUserHandler
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private MailerInterface $mailerInterface,
    ) {}

    public function __invoke(CreateUserCommand $command): void
    {
        $this->userRepository->save(new User(
            $command->email
        ));

        $this->mailerInterface->send($command->email, 'Potwierdzenie rejestracji', 'Zarejestrowano konto w systemie. Aktywacja konta trwa do 24h');

        $this->userRepository->flush();
    }
}
