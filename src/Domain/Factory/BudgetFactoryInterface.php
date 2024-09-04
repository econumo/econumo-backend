<?php

declare(strict_types=1);

namespace App\Domain\Factory;

use App\Domain\Entity\Budget;
use App\Domain\Entity\ValueObject\BudgetName;
use App\Domain\Entity\ValueObject\Id;
use DateTimeInterface;

interface BudgetFactoryInterface
{
    /**
     * @param Id $userId
     * @param Id $id
     * @param BudgetName $name
     * @param Id[] $excludedAccountsIds
     * @param DateTimeInterface $startDate
     * @return Budget
     */
    public function create(
        Id $userId,
        Id $id,
        BudgetName $name,
        array $excludedAccountsIds,
        DateTimeInterface $startDate
    ): Budget;
}
