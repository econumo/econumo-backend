<?php

declare(strict_types=1);


namespace App\EconumoOneBundle\Domain\Service\Budget;


use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;
use App\EconumoOneBundle\Domain\Factory\BudgetElementAmountFactoryInterface;
use App\EconumoOneBundle\Domain\Repository\BudgetElementAmountRepositoryInterface;
use App\EconumoOneBundle\Domain\Repository\BudgetElementOptionRepositoryInterface;

readonly class BudgetLimitService implements BudgetLimitServiceInterface
{
    public function __construct(
        private BudgetElementOptionRepositoryInterface $budgetElementOptionRepository,
        private BudgetElementAmountRepositoryInterface $budgetElementAmountRepository,
        private BudgetElementAmountFactoryInterface $budgetElementAmountFactory
    ) {
    }

    public function setLimit(Id $budgetId, Id $elementId, \DateTimeInterface $period, ?float $amount): void
    {
        $elementOption = $this->budgetElementOptionRepository->get($budgetId, $elementId, null);
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
                    $elementOption->getElementType(),
                    $amount,
                    $period
                );
            }
            $elementAmount->updateAmount($amount);
            $this->budgetElementAmountRepository->save([$elementAmount]);
        }
    }
}
