<?php

declare(strict_types=1);

namespace App\EconumoBundle\Domain\Service;

use App\EconumoBundle\Domain\Entity\ValueObject\DecimalNumber;
use Throwable;
use App\EconumoBundle\Domain\Entity\Account;
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
        } catch (Throwable $throwable) {
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
        } catch (Throwable $throwable) {
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
        } catch (Throwable $throwable) {
            $this->antiCorruptionService->rollback(__METHOD__);
            throw $throwable;
        }
    }

    public function updateBalance(
        Id $accountId,
        DecimalNumber $correction,
        DateTimeInterface $updatedAt,
        string $comment = ''
    ): Transaction {
        $this->antiCorruptionService->beginTransaction(__METHOD__);
        try {
            $transaction = $this->transactionFactory->createCorrection($accountId, $correction, $updatedAt, $comment);
            $this->transactionRepository->save([$transaction]);
            $this->antiCorruptionService->commit(__METHOD__);
        } catch (Throwable $throwable) {
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

    public function exportTransactionList(Id $userId): array
    {
        $accounts = $this->accountRepository->getUserAccounts($userId);
        $accountsById = [];
        foreach ($accounts as $account) {
            if (!$account->getUserId()->isEqual($userId)) {
                continue;
            }

            $accountsById[$account->getId()->getValue()] = $account;
        }

        $rows = [$this->getExportHeaders()];
        if ($accountsById === []) {
            return $rows;
        }

        $transactions = $this->transactionRepository->findAvailableForUserId($userId);
        foreach ($transactions as $transaction) {
            foreach ($this->buildExportRows($transaction, $accountsById) as $row) {
                $rows[] = $row;
            }
        }

        return $rows;
    }

    /**
     * @return array<int, string>
     */
    private function getExportHeaders(): array
    {
        return [
            'transaction_id',
            'account_name',
            'account_currency',
            'category',
            'description',
            'tag',
            'payee',
            'amount',
            'spent_at',
        ];
    }

    /**
     * @param array<string, Account> $accountsById
     * @return array<int, array<int, string>>
     */
    private function buildExportRows(Transaction $transaction, array $accountsById): array
    {
        $rows = [];
        $accountId = $transaction->getAccountId()->getValue();
        if (isset($accountsById[$accountId])) {
            $description = $transaction->getDescription();
            if ($transaction->getType()->isTransfer()) {
                $recipientId = $transaction->getAccountRecipientId()?->getValue();
                if ($recipientId && isset($accountsById[$recipientId])) {
                    $description = sprintf('transfer to %s', $accountsById[$recipientId]->getName()->getValue());
                }
            }

            $rows[] = $this->buildExportRow(
                $transaction,
                $accountsById[$accountId],
                $transaction->getAmount(),
                $transaction->getCategory()?->getName()->getValue(),
                $transaction->getTag()?->getName()->getValue(),
                $transaction->getPayee()?->getName()->getValue(),
                $description
            );
        }

        if ($transaction->getType()->isTransfer()) {
            $recipientId = $transaction->getAccountRecipientId()?->getValue();
            if ($recipientId && isset($accountsById[$recipientId])) {
                $rows[] = $this->buildExportRow(
                    $transaction,
                    $accountsById[$recipientId],
                    $transaction->getAmountRecipient() ?? $transaction->getAmount(),
                    '',
                    '',
                    '',
                    sprintf('transfer from %s', $accountsById[$accountId]->getName()->getValue())
                );
            }
        }

        return $rows;
    }

    private function buildExportRow(
        Transaction $transaction,
        Account $account,
        DecimalNumber $amount,
        ?string $category,
        ?string $tag,
        ?string $payee,
        ?string $description = null
    ): array {
        return [
            $transaction->getId()->getValue(),
            $account->getName()->getValue(),
            $account->getCurrencyCode()->getValue(),
            $category ?? '',
            $description ?? $transaction->getDescription(),
            $tag ?? '',
            $payee ?? '',
            $amount->getValue(),
            $transaction->getSpentAt()->format('Y-m-d H:i:s'),
        ];
    }
}
