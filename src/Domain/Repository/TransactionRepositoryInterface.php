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

    /**
     * @param Transaction[] $transactions
     */
    public function save(array $transactions): void;

    /**
     * @return Transaction[]
     */
    public function findAvailableForUserId(Id $userId): array;

    /**
     * @return Transaction[]
     */
    public function findChanges(Id $userId, DateTimeInterface $lastUpdate): array;

    public function get(Id $id): Transaction;

    public function delete(Transaction $transaction): void;

    public function replaceCategory(Id $oldCategoryId, Id $newCategoryId): void;
}
