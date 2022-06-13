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
     * @param Id $accountId
     * @return Transaction[]
     */
    public function findByAccountId(Id $accountId): array;

    public function save(Transaction ...$transactions): void;

    /**
     * @param Id $userId
     * @return Transaction[]
     */
    public function findAvailableForUserId(Id $userId): array;

    /**
     * @param Id $userId
     * @param DateTimeInterface $lastUpdate
     * @return Transaction[]
     */
    public function findChanges(Id $userId, DateTimeInterface $lastUpdate): array;

    public function get(Id $id): Transaction;

    public function delete(Transaction $transaction): void;

    public function replaceCategory(Id $oldCategoryId, Id $newCategoryId): void;
}
