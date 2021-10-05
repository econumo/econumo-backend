<?php

declare(strict_types=1);

namespace App\Domain\Factory;

use App\Domain\Entity\Transaction;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Entity\ValueObject\TransactionType;
use App\Domain\Repository\AccountRepositoryInterface;
use App\Domain\Repository\TransactionRepositoryInterface;
use App\Domain\Service\DatetimeServiceInterface;
use App\Domain\Service\Dto\TransactionDto;

class TransactionFactory implements TransactionFactoryInterface
{
    private DatetimeServiceInterface $datetimeService;
    private TransactionRepositoryInterface $transactionRepository;
    private AccountRepositoryInterface $accountRepository;

    public function __construct(DatetimeServiceInterface $datetimeService, TransactionRepositoryInterface $transactionRepository, AccountRepositoryInterface $accountRepository)
    {
        $this->datetimeService = $datetimeService;
        $this->transactionRepository = $transactionRepository;
        $this->accountRepository = $accountRepository;
    }

    public function create(TransactionDto $dto): Transaction
    {
        return new Transaction(
            $dto->id,
            $dto->userId,
            $dto->type,
            $dto->accountId,
            $dto->categoryId,
            $dto->amount,
            $dto->date,
            $this->datetimeService->getCurrentDatetime(),
            $dto->accountRecipientId,
            $dto->amountRecipient,
            $dto->description,
            $dto->payeeId,
            $dto->tagId
        );
    }

    public function createCorrection(Id $accountId, float $correction): Transaction
    {
        $account = $this->accountRepository->get($accountId);
        return new Transaction(
            $this->transactionRepository->getNextIdentity(),
            $account->getUserId(),
            new TransactionType($correction < 0 ? TransactionType::INCOME : TransactionType::EXPENSE),
            $accountId,
            null,
            abs($correction),
            $this->datetimeService->getCurrentDatetime(),
            $this->datetimeService->getCurrentDatetime(),
            null,
            null,
            '',
            null,
            null
        );
    }
}
