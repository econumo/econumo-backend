<?php

declare(strict_types=1);


namespace App\EconumoOneBundle\Domain\Factory;


use App\EconumoOneBundle\Domain\Entity\BudgetElementLimit;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;
use App\EconumoOneBundle\Domain\Repository\BudgetElementRepositoryInterface;
use App\EconumoOneBundle\Domain\Repository\BudgetRepositoryInterface;
use App\EconumoOneBundle\Domain\Service\DatetimeServiceInterface;
use DateTimeInterface;

readonly class BudgetElementLimitFactory implements BudgetElementLimitFactoryInterface
{
    public function __construct(
        private DatetimeServiceInterface $datetimeService,
        private BudgetRepositoryInterface $budgetRepository,
        private BudgetElementRepositoryInterface $budgetElementRepository,
    ) {
    }

    public function create(
        Id $budgetId,
        Id $elementId,
        float $amount,
        DateTimeInterface $period
    ): BudgetElementLimit {
        return new BudgetElementLimit(
            $this->budgetRepository->getReference($budgetId),
            $this->budgetElementRepository->getReference($elementId),
            $amount,
            $period,
            $this->datetimeService->getCurrentDatetime()
        );
    }
}
