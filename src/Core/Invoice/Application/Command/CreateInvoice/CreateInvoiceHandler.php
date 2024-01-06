<?php

namespace App\Core\Invoice\Application\Command\CreateInvoice;

use App\Core\Invoice\Domain\Invoice;
use App\Core\Invoice\Domain\Repository\InvoiceRepositoryInterface;
use App\Core\User\Domain\Repository\UserRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use App\Core\User\Domain\Status\UserStatus;

#[AsMessageHandler]
class CreateInvoiceHandler
{
    public function __construct(
        private readonly InvoiceRepositoryInterface $invoiceRepository,
        private readonly UserRepositoryInterface $userRepository
    ) {}

    public function __invoke(CreateInvoiceCommand $command): void
    {
        $this->invoiceRepository->save(new Invoice(
            $this->userRepository->getByEmailAndStatus($command->email, UserStatus::ACTIVE),
            $command->amount
        ));

        $this->invoiceRepository->flush();
    }
}
