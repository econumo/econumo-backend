<?php

declare(strict_types=1);

namespace App\EconumoBundle\Domain\Factory;

use App\EconumoBundle\Domain\Entity\Transaction;
use App\EconumoBundle\Domain\Entity\ValueObject\Id;
use App\EconumoBundle\Domain\Entity\ValueObject\TransactionType;
use App\EconumoBundle\Domain\Exception\RecipientIsRequiredException;
use App\EconumoBundle\Domain\Repository\AccountRepositoryInterface;
use App\EconumoBundle\Domain\Repository\CategoryRepositoryInterface;
use App\EconumoBundle\Domain\Repository\PayeeRepositoryInterface;
use App\EconumoBundle\Domain\Repository\TagRepositoryInterface;
use App\EconumoBundle\Domain\Factory\TransactionFactoryInterface;
use App\EconumoBundle\Domain\Repository\TransactionRepositoryInterface;
use App\EconumoBundle\Domain\Repository\UserRepositoryInterface;
use App\EconumoBundle\Domain\Service\DatetimeServiceInterface;
use App\EconumoBundle\Domain\Service\Dto\TransactionDto;

class TransactionFactory implements TransactionFactoryInterface
{
    public function __construct(private readonly DatetimeServiceInterface $datetimeService, private readonly TransactionRepositoryInterface $transactionRepository, private readonly AccountRepositoryInterface $accountRepository, private readonly UserRepositoryInterface $userRepository, private readonly CategoryRepositoryInterface $categoryRepository, private readonly PayeeRepositoryInterface $payeeRepository, private readonly TagRepositoryInterface $tagRepository)
    {
    }

    public function create(TransactionDto $dto): Transaction
    {
        if ($dto->type->isTransfer() && $dto->accountRecipientId === null) {
            throw new RecipientIsRequiredException('Recipient account is required for transfer transaction');
        }
        return new Transaction(
            $this->transactionRepository->getNextIdentity(),
            $this->userRepository->getReference($dto->userId),
            $dto->type,
            $this->accountRepository->getReference($dto->accountId),
            ($dto->categoryId === null ? null : $this->categoryRepository->getReference($dto->categoryId)),
            $dto->amount,
            $dto->date,
            $this->datetimeService->getCurrentDatetime(),
            ($dto->accountRecipientId === null ? null : $this->accountRepository->getReference($dto->accountRecipientId)),
            $dto->amountRecipient,
            $dto->description,
            ($dto->payeeId === null ? null : $this->payeeRepository->getReference($dto->payeeId)),
            ($dto->tagId === null ? null :  $this->tagRepository->getReference($dto->tagId)),
        );
    }

    public function createTransaction(
        Id $accountId,
        float $transaction,
        \DateTimeInterface $transactionDate,
        string $comment = ''
    ): Transaction {
        $account = $this->accountRepository->get($accountId);
        return new Transaction(
            $this->transactionRepository->getNextIdentity(),
            $this->userRepository->getReference($account->getUserId()),
            new TransactionType($transaction < 0 ? TransactionType::EXPENSE : TransactionType::INCOME),
            $this->accountRepository->getReference($accountId),
            null,
            abs($transaction),
            $transactionDate,
            $this->datetimeService->getCurrentDatetime(),
            null,
            null,
            $comment,
            null,
            null
        );
    }

    public function createCorrection(
        Id $accountId,
        float $correction,
        \DateTimeInterface $transactionDate,
        string $comment = ''
    ): Transaction {
        $account = $this->accountRepository->get($accountId);
        return new Transaction(
            $this->transactionRepository->getNextIdentity(),
            $this->userRepository->getReference($account->getUserId()),
            new TransactionType($correction < 0 ? TransactionType::INCOME : TransactionType::EXPENSE),
            $this->accountRepository->getReference($accountId),
            null,
            abs($correction),
            $transactionDate,
            $this->datetimeService->getCurrentDatetime(),
            null,
            null,
            $comment,
            null,
            null
        );
    }
}
