<?php

declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\Entity\Transaction;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Factory\TransactionFactoryInterface;
use App\Domain\Repository\AccountRepositoryInterface;
use App\Domain\Repository\TransactionRepositoryInterface;
use App\Domain\Service\Dto\TransactionDto;

class TransactionService implements TransactionServiceInterface
{
    private TransactionRepositoryInterface $transactionRepository;
    private TransactionFactoryInterface $transactionFactory;
    private AccountRepositoryInterface $accountRepository;

    public function __construct(
        TransactionRepositoryInterface $transactionRepository,
        TransactionFactoryInterface $transactionFactory,
        AccountRepositoryInterface $accountRepository
    ) {
        $this->transactionRepository = $transactionRepository;
        $this->transactionFactory = $transactionFactory;
        $this->accountRepository = $accountRepository;
    }

    public function createTransaction(TransactionDto $transactionDto): Transaction
    {
        $transaction = $this->transactionFactory->create($transactionDto);
        $this->transactionRepository->save($transaction);

        $account = $this->accountRepository->get($transactionDto->accountId);
        $account->applyTransaction($transaction);
        $this->accountRepository->save($account);
        if ($transactionDto->type->isTransfer() && $transactionDto->accountRecipientId !== null) {
            $accountRecipient = $this->accountRepository->get($transactionDto->accountRecipientId);
            $accountRecipient->applyTransaction($transaction);
            $this->accountRepository->save($accountRecipient);
        }

        return $transaction;
    }

    public function deleteTransaction(Transaction $transaction): void
    {
        $this->transactionRepository->delete($transaction);

        $account = $this->accountRepository->get($transaction->getAccountId());
        $account->rollbackTransaction($transaction);
        $this->accountRepository->save($account);
        if ($transaction->getType()->isTransfer() && $transaction->getAccountRecipientId() !== null) {
            $accountRecipient = $this->accountRepository->get($transaction->getAccountRecipientId());
            $accountRecipient->rollbackTransaction($transaction);
            $this->accountRepository->save($accountRecipient);
        }
    }

    public function updateBalance(Id $accountId, float $correction, \DateTimeInterface $updatedAt, string $comment = ''): Transaction
    {
        $transaction = $this->transactionFactory->createCorrection($accountId, $correction, $updatedAt, $comment);
        $this->transactionRepository->save($transaction);
        $account = $this->accountRepository->get($accountId);
        $account->applyTransaction($transaction);
        $this->accountRepository->save($account);

        return $transaction;
    }
}
