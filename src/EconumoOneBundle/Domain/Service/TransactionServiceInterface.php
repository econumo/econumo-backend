<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Domain\Service;

use App\EconumoOneBundle\Domain\Entity\Transaction;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;
use App\EconumoOneBundle\Domain\Service\Dto\TransactionDto;
use DateTimeInterface;

interface TransactionServiceInterface
{
    public function createTransaction(TransactionDto $transactionDto): Transaction;

    public function updateTransaction(Id $id, TransactionDto $transactionDto): Transaction;

    public function deleteTransaction(Transaction $transaction): void;

    public function updateBalance(Id $accountId, float $correction, DateTimeInterface $updatedAt, string $comment = ''): Transaction;

    /**
     * @param Id $userId
     * @return Transaction[]
     */
    public function getTransactionsForVisibleAccounts(Id $userId, DateTimeInterface $periodStart = null, DateTimeInterface $periodEnd = null): array;
}
