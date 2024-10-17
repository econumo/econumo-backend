<?php

declare(strict_types=1);


namespace App\EconumoOneBundle\Domain\Service\Budget;


use App\EconumoOneBundle\Domain\Entity\ValueObject\BudgetName;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;
use App\EconumoOneBundle\Domain\Service\Budget\Dto\BudgetDto;
use App\EconumoOneBundle\Domain\Service\Budget\Dto\BudgetMetaDto;
use App\EconumoOneBundle\Domain\Service\Budget\Dto\BudgetStructureMoveElementDto;
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

    /**
     * @param Id $userId
     * @param Id $budgetId
     * @param BudgetName $name
     * @param Id[] $excludedAccountsIds
     * @return BudgetMetaDto
     */
    public function updateBudget(
        Id $userId,
        Id $budgetId,
        BudgetName $name,
        array $excludedAccountsIds = []
    ): BudgetMetaDto;

    public function excludeAccount(Id $userId, Id $budgetId, Id $accountId): BudgetMetaDto;

    public function includeAccount(Id $userId, Id $budgetId, Id $accountId): BudgetMetaDto;

    public function resetBudget(Id $userId, Id $budgetId, DateTimeInterface $startedAt): BudgetMetaDto;

    public function getBudget(Id $userId, Id $budgetId, DateTimeInterface $periodStart): BudgetDto;

    /**
     * @param Id $userId
     * @param Id $budgetId
     * @param BudgetStructureMoveElementDto[] $affectedElements
     * @return void
     */
    public function moveElements(Id $userId, Id $budgetId, array $affectedElements): void;
}
