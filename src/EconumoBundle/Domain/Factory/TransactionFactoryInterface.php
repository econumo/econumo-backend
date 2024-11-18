<?php
declare(strict_types=1);

namespace App\EconumoBundle\Domain\Factory;

use DateTimeInterface;
use App\EconumoBundle\Domain\Entity\Transaction;
use App\EconumoBundle\Domain\Entity\ValueObject\Id;
use App\EconumoBundle\Domain\Service\Dto\TransactionDto;

interface TransactionFactoryInterface
{
    public function create(TransactionDto $dto): Transaction;

    public function createTransaction(Id $accountId, float $transaction, DateTimeInterface $transactionDate, string $comment = ''): Transaction;

    public function createCorrection(Id $accountId, float $correction, DateTimeInterface $transactionDate, string $comment = ''): Transaction;
}
