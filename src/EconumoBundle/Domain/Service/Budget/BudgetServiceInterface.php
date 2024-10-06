<?php

declare(strict_types=1);


namespace App\EconumoBundle\Domain\Service\Budget;


use App\EconumoBundle\Domain\Entity\ValueObject\BudgetName;
use App\EconumoBundle\Domain\Entity\ValueObject\Id;
use App\EconumoBundle\Domain\Service\Budget\Dto\BudgetDto;
use App\EconumoBundle\Domain\Service\Budget\Dto\BudgetMetaDto;
use DateTimeInterface;

interface BudgetServiceInterface
{
    /**
     * @param Id $userId User ID
     * @param Id $budgetId Budget ID
     * @param BudgetName $name Budget name
     * @param DateTimeInterface|null $startDate
     * @param Id|null $currencyId
     * @param Id[] $excludedAccountsIds
     * @return BudgetDto
     */
    public function createBudget(
        Id $userId,
        Id $budgetId,
        BudgetName $name,
        ?DateTimeInterface $startDate,
        ?Id $currencyId,
        array $excludedAccountsIds = []
    ): BudgetDto;

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
