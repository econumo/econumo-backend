<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\Transaction;
use App\Domain\Entity\ValueObject\Id;

interface TransactionRepositoryInterface
{
    /**
     * @param Id $id
     * @return Transaction[]
     */
    public function findByAccountId(Id $id): array;

    public function save(Transaction ...$transactions): void;

    public function findByUserId(Id $userId): array;

    public function get(Id $id): Transaction;

    public function delete(Transaction $transaction): void;
}
