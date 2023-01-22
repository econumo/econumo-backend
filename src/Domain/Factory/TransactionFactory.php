<?php

declare(strict_types=1);

namespace App\Domain\Factory;

use App\Domain\Entity\Transaction;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Entity\ValueObject\TransactionType;
use App\Domain\Repository\AccountRepositoryInterface;
use App\Domain\Repository\CategoryRepositoryInterface;
use App\Domain\Repository\PayeeRepositoryInterface;
use App\Domain\Repository\TagRepositoryInterface;
use App\Domain\Repository\TransactionRepositoryInterface;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\Service\DatetimeServiceInterface;
use App\Domain\Service\Dto\TransactionDto;

class TransactionFactory implements TransactionFactoryInterface
{
    private DatetimeServiceInterface $datetimeService;

    private TransactionRepositoryInterface $transactionRepository;

    private AccountRepositoryInterface $accountRepository;

    private UserRepositoryInterface $userRepository;

    private CategoryRepositoryInterface $categoryRepository;

    private PayeeRepositoryInterface $payeeRepository;

    private TagRepositoryInterface $tagRepository;

    public function __construct(
        DatetimeServiceInterface $datetimeService,
        TransactionRepositoryInterface $transactionRepository,
        AccountRepositoryInterface $accountRepository,
        UserRepositoryInterface $userRepository,
        CategoryRepositoryInterface $categoryRepository,
        PayeeRepositoryInterface $payeeRepository,
        TagRepositoryInterface $tagRepository
    ) {
        $this->datetimeService = $datetimeService;
        $this->transactionRepository = $transactionRepository;
        $this->accountRepository = $accountRepository;
        $this->userRepository = $userRepository;
        $this->categoryRepository = $categoryRepository;
        $this->payeeRepository = $payeeRepository;
        $this->tagRepository = $tagRepository;
    }

    public function create(TransactionDto $dto): Transaction
    {
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
