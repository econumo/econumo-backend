<?php

declare(strict_types=1);

namespace App\Domain\Service\Budget;

use App\Domain\Entity\ValueObject\Id;

interface BudgetAccessServiceInterface
{
    public function canReadBudget(Id $userId, Id $budgetId): bool;
}
