<?php

declare(strict_types=1);


namespace App\EconumoOneBundle\Domain\Service\Budget;


use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;
use App\EconumoOneBundle\Domain\Repository\BudgetElementLimitRepositoryInterface;
use App\EconumoOneBundle\Domain\Repository\BudgetElementRepositoryInterface;
use App\EconumoOneBundle\Domain\Repository\BudgetRepositoryInterface;
use App\EconumoOneBundle\Domain\Service\AntiCorruptionServiceInterface;

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
