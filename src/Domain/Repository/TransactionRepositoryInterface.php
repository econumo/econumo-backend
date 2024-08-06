<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\Transaction;
use App\Domain\Entity\ValueObject\Id;
use DateTimeInterface;

interface TransactionRepositoryInterface
{
    public function getNextIdentity(): Id;

    /**
     * @return Transaction[]
     */
    public function findByAccountId(Id $accountId): array;

    public function getBalance(Id $accountId, DateTimeInterface $date): float;

    /**
     * @param Transaction[] $transactions
     */
    public function save(array $transactions): void;

    /**
     * @param Id[] $excludeAccounts
     * @return Transaction[]
     */
    public function findAvailableForUserId(Id $userId, array $excludeAccounts = [], DateTimeInterface $periodStart = null, DateTimeInterface $periodEnd = null): array;

    /**
     * @return Transaction[]
     */
    public function findChanges(Id $userId, DateTimeInterface $lastUpdate): array;

    public function get(Id $id): Transaction;

    public function delete(Transaction $transaction): void;

    public function replaceCategory(Id $oldCategoryId, Id $newCategoryId): void;

    /**
     * @param Id[] $categoryIds
     * @param DateTimeInterface $startDate
     * @param DateTimeInterface $endDate
     * @return array
     */
    public function countSpendingForCategories(array $categoryIds, DateTimeInterface $startDate, DateTimeInterface $endDate): array;

    /**
     * @param Id[] $tagsIds
     * @param DateTimeInterface $startDate
     * @param DateTimeInterface $endDate
     * @return array
     */
    public function countSpendingForTags(array $tagsIds, DateTimeInterface $startDate, DateTimeInterface $endDate): array;

    /**
     * @param Id[] $accountIds
     * @param DateTimeInterface $periodStart
     * @param DateTimeInterface $periodEnd
     * @return array
     */
    public function getAccountsReport(array $accountIds, DateTimeInterface $periodStart, DateTimeInterface $periodEnd): array;

    /**
     * @param Id[] $reportAccountIds
     * @param Id[] $hoardAccountIds
     * @param DateTimeInterface $periodStart
     * @param DateTimeInterface $periodEnd
     * @return array
     */
    public function getHoardsReport(array $reportAccountIds, array $hoardAccountIds, DateTimeInterface $periodStart, DateTimeInterface $periodEnd): array;
}
