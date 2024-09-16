<?php

declare(strict_types=1);


namespace App\Domain\Service\Budget;


use App\Domain\Entity\Budget;
use App\Domain\Entity\ValueObject\BudgetName;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Service\Budget\Dto\BudgetDto;
use App\Domain\Service\Budget\Dto\BudgetPreviewDto;

interface BudgetServiceInterface
{
    /**
     * @param Id $userId User ID
     * @param Id $budgetId Budget ID
     * @param BudgetName $name Budget name
     * @param Id[] $excludedAccountsIds
     * @return Budget
     */
    public function createBudget(Id $userId, Id $budgetId, BudgetName $name, array $excludedAccountsIds = []): BudgetDto;

    /**
     * @param Id $userId
     * @param Id $budgetId
     * @return BudgetDto
     */
    public function getBudget(Id $userId, Id $budgetId): BudgetDto;

    /**
     * @param Id $userId
     * @return BudgetPreviewDto[]
     */
    public function getBudgetList(Id $userId): array;
}
