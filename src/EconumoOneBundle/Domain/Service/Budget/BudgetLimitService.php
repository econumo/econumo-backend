<?php

declare(strict_types=1);


namespace App\EconumoOneBundle\Domain\Service\Budget;


use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;
use App\EconumoOneBundle\Domain\Factory\BudgetElementLimitFactoryInterface;
use App\EconumoOneBundle\Domain\Repository\BudgetElementLimitRepositoryInterface;
use App\EconumoOneBundle\Domain\Repository\BudgetElementRepositoryInterface;

readonly class BudgetLimitService implements BudgetLimitServiceInterface
{
    public function __construct(
        private BudgetElementRepositoryInterface $budgetElementRepository,
        private BudgetElementLimitRepositoryInterface $budgetElementLimitRepository,
        private BudgetElementLimitFactoryInterface $budgetElementLimitFactory
    ) {
    }

    public function setLimit(Id $budgetId, Id $elementId, \DateTimeInterface $period, ?float $amount): void
    {
        $elementAmount = $this->budgetElementLimitRepository->get($budgetId, $elementId, $period);
        if (null === $amount) {
            if (null !== $elementAmount) {
                $this->budgetElementLimitRepository->delete([$elementAmount]);
            }
        } else {
            if (null === $elementAmount) {
                $elementAmount = $this->budgetElementLimitFactory->create(
                    $budgetId,
                    $elementId,
                    $amount,
                    $period
                );
            }
            $elementAmount->updateAmount($amount);
            $this->budgetElementLimitRepository->save([$elementAmount]);
        }
    }
}
