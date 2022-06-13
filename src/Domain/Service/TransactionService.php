<?php

declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\Entity\Transaction;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Factory\TransactionFactoryInterface;
use App\Domain\Repository\AccountRepositoryInterface;
use App\Domain\Repository\TransactionRepositoryInterface;
use App\Domain\Service\Dto\TransactionDto;
use DateTimeInterface;

class TransactionService implements TransactionServiceInterface
{
    private TransactionRepositoryInterface $transactionRepository;
    private TransactionFactoryInterface $transactionFactory;
    private AccountRepositoryInterface $accountRepository;
    private AntiCorruptionServiceInterface $antiCorruptionService;

    public function __construct(
        TransactionRepositoryInterface $transactionRepository,
        TransactionFactoryInterface $transactionFactory,
        AccountRepositoryInterface $accountRepository,
        AntiCorruptionServiceInterface $antiCorruptionService
    ) {
        $this->transactionRepository = $transactionRepository;
        $this->transactionFactory = $transactionFactory;
        $this->accountRepository = $accountRepository;
        $this->antiCorruptionService = $antiCorruptionService;
    }

    public function createTransaction(TransactionDto $transactionDto): Transaction
    {
        $this->antiCorruptionService->beginTransaction();
        try {
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
            $this->antiCorruptionService->commit();
        } catch (\Throwable $exception) {
            $this->antiCorruptionService->rollback();
            throw $exception;
        }

        return $transaction;
    }

    public function updateTransaction(Id $id, TransactionDto $transactionDto): Transaction
    {
        $this->antiCorruptionService->beginTransaction();
        $transaction = $this->transactionRepository->get($id);
        try {
            $account = $this->accountRepository->get($transaction->getAccountId());
            $account->rollbackTransaction($transaction);
            $this->accountRepository->save($account);
            if ($transaction->getType()->isTransfer() && $transaction->getAccountRecipientId() !== null) {
                $accountRecipient = $this->accountRepository->get($transaction->getAccountRecipientId());
                $accountRecipient->rollbackTransaction($transaction);
                $this->accountRepository->save($accountRecipient);
            }

            $transaction->update($transactionDto);
            $this->transactionRepository->save($transaction);
            if (!$account->getId()->isEqual($transaction->getAccountId())) {
                $account = $this->accountRepository->get($transaction->getAccountId());
            }
            $account->applyTransaction($transaction);
            $this->accountRepository->save($account);
            if ($transaction->getType()->isTransfer() && $transaction->getAccountRecipientId() !== null) {
                $accountRecipient = $this->accountRepository->get($transaction->getAccountRecipientId());
                $accountRecipient->applyTransaction($transaction);
                $this->accountRepository->save($accountRecipient);
            }

            $this->antiCorruptionService->commit();
        } catch (\Throwable $exception) {
            $this->antiCorruptionService->rollback();
            throw $exception;
        }

        return $transaction;
    }

    public function deleteTransaction(Transaction $transaction): void
    {
        $this->antiCorruptionService->beginTransaction();
        try {
            $account = $this->accountRepository->get($transaction->getAccountId());
            $account->rollbackTransaction($transaction);
            $this->accountRepository->save($account);
            if ($transaction->getType()->isTransfer() && $transaction->getAccountRecipientId() !== null) {
                $accountRecipient = $this->accountRepository->get($transaction->getAccountRecipientId());
                $accountRecipient->rollbackTransaction($transaction);
                $this->accountRepository->save($accountRecipient);
            }
            $this->transactionRepository->delete($transaction);
            $this->antiCorruptionService->commit();
        } catch (\Throwable $exception) {
            $this->antiCorruptionService->rollback();
            throw $exception;
        }
    }

    public function updateBalance(Id $accountId, float $correction, \DateTimeInterface $updatedAt, string $comment = ''): Transaction
    {
        $this->antiCorruptionService->beginTransaction();
        try {
            $transaction = $this->transactionFactory->createCorrection($accountId, $correction, $updatedAt, $comment);
            $this->transactionRepository->save($transaction);
            $account = $this->accountRepository->get($accountId);
            $account->applyTransaction($transaction);
            $this->accountRepository->save($account);
            $this->antiCorruptionService->commit();
        } catch (\Throwable $exception) {
            $this->antiCorruptionService->rollback();
            throw $exception;
        }

        return $transaction;
    }

    public function getChanged(Id $userId, DateTimeInterface $lastUpdate): array
    {
        return $this->transactionRepository->findChanges($userId, $lastUpdate);
    }
}
