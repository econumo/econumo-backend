<?php

declare(strict_types=1);


namespace App\EconumoBundle\Domain\Service\Budget;


use App\EconumoBundle\Domain\Entity\ValueObject\Id;
use App\EconumoBundle\Domain\Repository\BudgetRepositoryInterface;

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
