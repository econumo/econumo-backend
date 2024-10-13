<?php
declare(strict_types=1);

namespace App\EconumoOneBundle\Domain\Factory;

use App\EconumoOneBundle\Domain\Entity\Transaction;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;
use App\EconumoOneBundle\Domain\Service\Dto\TransactionDto;

interface TransactionFactoryInterface
{
    public function create(TransactionDto $dto): Transaction;

    public function createTransaction(Id $accountId, float $transaction, \DateTimeInterface $transactionDate, string $comment = ''): Transaction;

    public function createCorrection(Id $accountId, float $correction, \DateTimeInterface $transactionDate, string $comment = ''): Transaction;
}
