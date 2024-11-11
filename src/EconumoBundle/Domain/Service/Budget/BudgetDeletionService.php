<?php

declare(strict_types=1);


namespace App\EconumoBundle\Domain\Service\Budget;


use App\EconumoBundle\Domain\Entity\ValueObject\Id;
use App\EconumoBundle\Domain\Repository\BudgetElementLimitRepositoryInterface;
use App\EconumoBundle\Domain\Repository\BudgetElementRepositoryInterface;
use App\EconumoBundle\Domain\Repository\BudgetRepositoryInterface;
use App\EconumoBundle\Domain\Service\AntiCorruptionServiceInterface;

readonly class BudgetDeletionService
{
    public function __construct(
        private BudgetRepositoryInterface $budgetRepository,
        private BudgetElementRepositoryInterface $budgetElementRepository,
        private BudgetElementLimitRepositoryInterface $budgetElementLimitRepository,
        private AntiCorruptionServiceInterface $antiCorruptionService
    ) {
    }

    public function deleteBudget(Id $budgetId): void
    {
        $budget = $this->budgetRepository->get($budgetId);
        $access = $budget->getAccessList();
        $budgetOwner = $budget->getUser();
        $this->antiCorruptionService->beginTransaction(__METHOD__);
        try {
            $this->budgetRepository->delete([$budget]);
            // @todo change the default budget for all budget users

            $this->antiCorruptionService->commit(__METHOD__);
        } catch (\Throwable $exception) {
            $this->antiCorruptionService->rollback(__METHOD__);
            throw $exception;
        }
    }
}
