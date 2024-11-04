<?php

declare(strict_types=1);


namespace App\EconumoOneBundle\Domain\Factory;


use App\EconumoOneBundle\Domain\Entity\BudgetElement;
use App\EconumoOneBundle\Domain\Entity\BudgetElementLimit;
use App\EconumoOneBundle\Domain\Repository\BudgetElementLimitRepositoryInterface;
use App\EconumoOneBundle\Domain\Service\DatetimeServiceInterface;
use DateTimeInterface;

readonly class BudgetElementLimitFactory implements BudgetElementLimitFactoryInterface
{
    public function __construct(
        private DatetimeServiceInterface $datetimeService,
        private BudgetElementLimitRepositoryInterface $budgetElementLimitRepository,
    ) {
    }

    public function create(
        BudgetElement $budgetElement,
        float $amount,
        DateTimeInterface $period
    ): BudgetElementLimit {
        return new BudgetElementLimit(
            $this->budgetElementLimitRepository->getNextIdentity(),
            $budgetElement,
            $amount,
            $period,
            $this->datetimeService->getCurrentDatetime()
        );
    }
}
