<?php

declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\Entity\Transaction;
use App\Domain\Service\Dto\TransactionDto;

interface TransactionServiceInterface
{
    public function createTransaction(TransactionDto $transactionDto): Transaction;
}
