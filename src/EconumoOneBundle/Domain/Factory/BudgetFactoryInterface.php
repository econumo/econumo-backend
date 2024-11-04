<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Domain\Factory;

use App\EconumoOneBundle\Domain\Entity\Budget;
use App\EconumoOneBundle\Domain\Entity\ValueObject\BudgetName;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;
use DateTimeInterface;

interface BudgetFactoryInterface
{
    /**
     * @param Id $userId
     * @param Id $id
     * @param BudgetName $name
     * @param DateTimeInterface $startDate
     * @param Id $currencyId
     * @param Id[] $excludedAccountsIds
     * @return Budget
     */
    public function create(
        Id $userId,
        Id $id,
        BudgetName $name,
        DateTimeInterface $startDate,
        Id $currencyId,
        array $excludedAccountsIds,
    ): Budget;
}
