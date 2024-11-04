<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Domain\Factory;

use App\EconumoOneBundle\Domain\Entity\Transaction;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;
use App\EconumoOneBundle\Domain\Entity\ValueObject\TransactionType;
use App\EconumoOneBundle\Domain\Exception\RecipientIsRequiredException;
use App\EconumoOneBundle\Domain\Repository\AccountRepositoryInterface;
use App\EconumoOneBundle\Domain\Repository\CategoryRepositoryInterface;
use App\EconumoOneBundle\Domain\Repository\PayeeRepositoryInterface;
use App\EconumoOneBundle\Domain\Repository\TagRepositoryInterface;
use App\EconumoOneBundle\Domain\Factory\TransactionFactoryInterface;
use App\EconumoOneBundle\Domain\Repository\TransactionRepositoryInterface;
use App\EconumoOneBundle\Domain\Repository\UserRepositoryInterface;
use App\EconumoOneBundle\Domain\Service\DatetimeServiceInterface;
use App\EconumoOneBundle\Domain\Service\Dto\TransactionDto;

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
