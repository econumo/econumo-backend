<?php

declare(strict_types=1);


namespace App\Domain\Service\Budget;


use App\Domain\Entity\Budget;
use App\Domain\Entity\ValueObject\BudgetName;
use App\Domain\Entity\ValueObject\Id;

interface BudgetServiceInterface
{
    /**
     * @param Id $userId User ID
     * @param Id $id Budget ID
     * @param BudgetName $name Budget name
     * @param Id[] $excludedAccountsIds
     * @return Budget
     */
    public function createBudget(Id $userId, Id $id, BudgetName $name, array $excludedAccountsIds = []): Budget;
}
