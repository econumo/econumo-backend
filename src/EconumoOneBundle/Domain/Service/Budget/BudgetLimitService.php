<?php

declare(strict_types=1);


namespace App\EconumoOneBundle\Domain\Service\Budget;


use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;
use App\EconumoOneBundle\Domain\Factory\BudgetElementAmountFactoryInterface;
use App\EconumoOneBundle\Domain\Repository\BudgetElementAmountRepositoryInterface;
use App\EconumoOneBundle\Domain\Repository\BudgetElementRepositoryInterface;

readonly class BudgetLimitService implements BudgetLimitServiceInterface
{
    public function __construct(
        private BudgetElementRepositoryInterface $budgetElementRepository,
        private BudgetElementAmountRepositoryInterface $budgetElementAmountRepository,
        private BudgetElementAmountFactoryInterface $budgetElementAmountFactory
    ) {
    }

    public function setLimit(Id $budgetId, Id $elementId, \DateTimeInterface $period, ?float $amount): void
    {
        $elementAmount = $this->budgetElementAmountRepository->get($budgetId, $elementId, $period);
        if (null === $amount) {
            if (null !== $elementAmount) {
                $this->budgetElementAmountRepository->delete([$elementAmount]);
            }
        } else {
            if (null === $elementAmount) {
                $elementAmount = $this->budgetElementAmountFactory->create(
                    $budgetId,
                    $elementId,
                    $amount,
                    $period
                );
            }
            $elementAmount->updateAmount($amount);
            $this->budgetElementAmountRepository->save([$elementAmount]);
        }
    }
}
