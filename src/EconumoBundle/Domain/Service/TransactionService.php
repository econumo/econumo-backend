<?php

declare(strict_types=1);

namespace App\EconumoBundle\Domain\Service;

use App\EconumoBundle\Domain\Entity\Transaction;
use App\EconumoBundle\Domain\Entity\ValueObject\Id;
use App\EconumoBundle\Domain\Factory\TransactionFactoryInterface;
use App\EconumoBundle\Domain\Repository\AccountRepositoryInterface;
use App\EconumoBundle\Domain\Repository\FolderRepositoryInterface;
use App\EconumoBundle\Domain\Repository\TransactionRepositoryInterface;
use App\EconumoBundle\Domain\Service\Dto\TransactionDto;
use DateTimeInterface;

readonly class TransactionService implements TransactionServiceInterface
{
    public function __construct(
        private TransactionRepositoryInterface $transactionRepository,
        private TransactionFactoryInterface $transactionFactory,
        private AccountRepositoryInterface $accountRepository,
        private AntiCorruptionServiceInterface $antiCorruptionService,
        private FolderRepositoryInterface $folderRepository
    ) {
    }

    public function createTransaction(TransactionDto $transactionDto): Transaction
    {
        $this->antiCorruptionService->beginTransaction(__METHOD__);
        try {
            $transaction = $this->transactionFactory->create($transactionDto);
            $this->transactionRepository->save([$transaction]);
            $this->antiCorruptionService->commit(__METHOD__);
        } catch (\Throwable $throwable) {
            $this->antiCorruptionService->rollback(__METHOD__);
            throw $throwable;
        }

        return $transaction;
    }

    public function updateTransaction(Id $id, TransactionDto $transactionDto): Transaction
    {
        $this->antiCorruptionService->beginTransaction(__METHOD__);
        $transaction = $this->transactionRepository->get($id);
        try {
            $transaction->update($transactionDto);
            $this->transactionRepository->save([$transaction]);
            $this->antiCorruptionService->commit(__METHOD__);
        } catch (\Throwable $throwable) {
            $this->antiCorruptionService->rollback(__METHOD__);
            throw $throwable;
        }

        return $transaction;
    }

    public function deleteTransaction(Transaction $transaction): void
    {
        $this->antiCorruptionService->beginTransaction(__METHOD__);
        try {
            $this->transactionRepository->delete($transaction);
            $this->antiCorruptionService->commit(__METHOD__);
        } catch (\Throwable $throwable) {
            $this->antiCorruptionService->rollback(__METHOD__);
            throw $throwable;
        }
    }

    public function updateBalance(
        Id $accountId,
        float $correction,
        \DateTimeInterface $updatedAt,
        string $comment = ''
    ): Transaction {
        $this->antiCorruptionService->beginTransaction(__METHOD__);
        try {
            $transaction = $this->transactionFactory->createCorrection($accountId, $correction, $updatedAt, $comment);
            $this->transactionRepository->save([$transaction]);
            $this->antiCorruptionService->commit(__METHOD__);
        } catch (\Throwable $throwable) {
            $this->antiCorruptionService->rollback(__METHOD__);
            throw $throwable;
        }

        return $transaction;
    }

    public function getChanged(Id $userId, DateTimeInterface $lastUpdate): array
    {
        return $this->transactionRepository->findChanges($userId, $lastUpdate);
    }

    /**
     * @inheritDoc
     */
    public function getTransactionsForVisibleAccounts(
        Id $userId,
        DateTimeInterface $periodStart = null,
        DateTimeInterface $periodEnd = null
    ): array {
        $folders = $this->folderRepository->getByUserId($userId);
        $accounts = $this->accountRepository->getUserAccounts($userId);
        $excludeAccountIds = [];
        foreach ($accounts as $account) {
            if ($account->isDeleted()) {
                $excludeAccountIds[] = $account->getId();
                continue;
            }
            foreach ($folders as $folder) {
                if ($folder->containsAccount($account) && !$folder->isVisible()) {
                    $excludeAccountIds[] = $account->getId();
                }
            }
        }

        return $this->transactionRepository->findAvailableForUserId(
            $userId,
            $excludeAccountIds,
            $periodStart,
            $periodEnd
        );
    }
}
