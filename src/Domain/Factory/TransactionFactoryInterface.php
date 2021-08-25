<?php
declare(strict_types=1);

namespace App\Domain\Factory;

use App\Domain\Entity\Transaction;
use App\Domain\Service\Dto\TransactionDto;

interface TransactionFactoryInterface
{
    public function create(TransactionDto $dto): Transaction;
}