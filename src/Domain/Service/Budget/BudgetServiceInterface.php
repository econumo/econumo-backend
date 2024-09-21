<?php

declare(strict_types=1);


namespace App\Domain\Service\Budget;


use App\Domain\Entity\ValueObject\BudgetName;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Service\Budget\Dto\BudgetDataDto;
use App\Domain\Service\Budget\Dto\BudgetStructureDto;
use App\Domain\Service\Budget\Dto\BudgetDto;
use DateTimeInterface;

interface BudgetServiceInterface
{
    /**
     * @param Id $userId User ID
     * @param Id $budgetId Budget ID
     * @param BudgetName $name Budget name
     * @param Id[] $excludedAccountsIds
     * @return BudgetStructureDto
     */
    public function createBudget(
        Id $userId,
        Id $budgetId,
        BudgetName $name,
        array $excludedAccountsIds = []
    ): BudgetStructureDto;

    /**
     * @param Id $userId
     * @param Id $budgetId
     * @return BudgetStructureDto
     */
    public function getStructure(Id $userId, Id $budgetId): BudgetStructureDto;

    /**
     * @param Id $userId
     * @return BudgetDto[]
     */
    public function getBudgetList(Id $userId): array;

    /**
     * @param Id $budgetId
     */
    public function deleteBudget(Id $budgetId): void;

    public function updateBudget(Id $userId, Id $budgetId, BudgetName $name): BudgetDto;

    public function excludeAccount(Id $userId, Id $budgetId, Id $accountId): BudgetDto;

    public function includeAccount(Id $userId, Id $budgetId, Id $accountId): BudgetDto;

    public function resetBudget(Id $userId, Id $budgetId, DateTimeInterface $startedAt): BudgetDto;

    public function getData(Id $userId, Id $budgetId, DateTimeInterface $period): BudgetDataDto;
}
