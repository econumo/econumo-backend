<?php

declare(strict_types=1);


namespace App\Domain\Service\Budget;


use App\Domain\Entity\Budget;
use App\Domain\Entity\ValueObject\BudgetName;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Factory\BudgetFactoryInterface;
use App\Domain\Repository\BudgetRepositoryInterface;
use App\Domain\Service\DatetimeServiceInterface;

readonly class BudgetService implements BudgetServiceInterface
{
    public function __construct(
        private BudgetFactoryInterface $budgetFactory,
        private BudgetRepositoryInterface $budgetRepository,
        private DatetimeServiceInterface $datetimeService
    ) {
    }

    public function createBudget(Id $userId, Id $id, BudgetName $name, array $excludedAccountsIds = []): Budget
    {
        $budget = $this->budgetFactory->create(
            $userId,
            $id,
            $name,
            $excludedAccountsIds,
            $this->datetimeService->getCurrentDatetime()
        );
        $this->budgetRepository->save([$budget]);

        return $budget;
    }
}
