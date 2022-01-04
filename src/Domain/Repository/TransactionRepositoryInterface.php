<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\Transaction;
use App\Domain\Entity\ValueObject\Id;

interface TransactionRepositoryInterface
{
    public function getNextIdentity(): Id;

    /**
     * @param Id $accountId
     * @return Transaction[]
     */
    public function findByAccountId(Id $accountId): array;

    public function save(Transaction ...$transactions): void;

    public function findAvailableForUserId(Id $userId): array;

    public function get(Id $id): Transaction;

    public function delete(Transaction $transaction): void;

    public function replaceCategory(Id $oldCategoryId, Id $newCategoryId): void;
}
