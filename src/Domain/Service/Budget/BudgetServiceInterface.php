<?php

declare(strict_types=1);


namespace App\Domain\Service\Budget;


use App\Domain\Entity\ValueObject\BudgetName;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Service\Budget\Dto\BudgetDto;
use App\Domain\Service\Budget\Dto\BudgetMetaDto;
use DateTimeInterface;

interface BudgetServiceInterface
{
    /**
     * @param Id $userId User ID
     * @param Id $budgetId Budget ID
     * @param BudgetName $name Budget name
     * @param Id[] $excludedAccountsIds
     * @return BudgetMetaDto
     */
    public function createBudget(
        Id $userId,
        Id $budgetId,
        BudgetName $name,
        array $excludedAccountsIds = []
    ): BudgetMetaDto;

    /**
     * @param Id $userId
     * @return BudgetMetaDto[]
     */
    public function getBudgetList(Id $userId): array;

    /**
     * @param Id $budgetId
     */
    public function deleteBudget(Id $budgetId): void;

    public function updateBudget(Id $userId, Id $budgetId, BudgetName $name): BudgetMetaDto;

    public function excludeAccount(Id $userId, Id $budgetId, Id $accountId): BudgetMetaDto;

    public function includeAccount(Id $userId, Id $budgetId, Id $accountId): BudgetMetaDto;

    public function resetBudget(Id $userId, Id $budgetId, DateTimeInterface $startedAt): BudgetMetaDto;

    public function getBudget($userId, $budgetId, DateTimeInterface $periodStart): BudgetDto;
}
