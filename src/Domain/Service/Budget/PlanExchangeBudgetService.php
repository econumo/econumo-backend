<?php

declare(strict_types=1);

namespace App\Domain\Service\Budget;

use App\Domain\Entity\ValueObject\Id;
use App\Domain\Repository\PlanExchangeBudgetRepositoryInterface;
use DateTimeInterface;

readonly class PlanExchangeBudgetService
{
    public function __construct(
        private PlanExchangeBudgetRepositoryInterface $planExchangeBudgetRepository
    ) {
    }

    public function getByPlan(Id $planId, DateTimeInterface $period): array
    {
        $data = $this->planExchangeBudgetRepository->getByPlanIdAndPeriod($planId, $period);
    }
}
