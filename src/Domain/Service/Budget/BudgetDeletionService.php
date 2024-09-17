<?php

declare(strict_types=1);


namespace App\Domain\Service\Budget;


use App\Domain\Entity\ValueObject\Id;
use App\Domain\Repository\BudgetRepositoryInterface;

readonly class BudgetDeletionService
{
    public function __construct(
        private BudgetRepositoryInterface $budgetRepository
    ) {
    }

    public function deleteBudget(Id $budgetId): void
    {
        $budget = $this->budgetRepository->get($budgetId);
        $access = $budget->getAccessList();
        $budgetOwner = $budget->getUser();
        $this->budgetRepository->delete([$budget]);
        // @todo change the default budget for all budget users
    }
}
