<?php

declare(strict_types=1);

namespace App\Domain\Service\Budget;

use App\Domain\Entity\ValueObject\Id;
use App\Domain\Entity\ValueObject\UserRole;

interface BudgetAccessServiceInterface
{
    public function canReadBudget(Id $userId, Id $budgetId): bool;

    public function canDeleteBudget(Id $userId, Id $budgetId): bool;

    public function canUpdateBudget(Id $userId, Id $budgetId): bool;

    public function canResetBudget(Id $userId, Id $budgetId): bool;

    public function getBudgetRole(Id $userId, Id $budgetId): UserRole;
}
