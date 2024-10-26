<?php

declare(strict_types=1);


namespace App\EconumoOneBundle\Domain\Service\Budget;


use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;
use App\EconumoOneBundle\Domain\Factory\BudgetElementLimitFactoryInterface;
use App\EconumoOneBundle\Domain\Repository\BudgetElementLimitRepositoryInterface;
use App\EconumoOneBundle\Domain\Repository\BudgetElementRepositoryInterface;
use DateTimeInterface;

readonly class BudgetLimitService implements BudgetLimitServiceInterface
{
    public function __construct(
        private BudgetElementLimitRepositoryInterface $budgetElementLimitRepository,
        private BudgetElementLimitFactoryInterface $budgetElementLimitFactory,
        private BudgetElementRepositoryInterface $budgetElementRepository,
    ) {
    }

    public function setLimit(Id $budgetId, Id $elementId, DateTimeInterface $period, ?float $amount): void
    {
        $element = $this->budgetElementRepository->get($budgetId, $elementId);
        $elementLimit = $this->budgetElementLimitRepository->get($element, $period);
        if (null === $amount) {
            if (null !== $elementLimit) {
                $this->budgetElementLimitRepository->delete([$elementLimit]);
            }
        } else {
            if (null === $elementLimit) {
                $elementLimit = $this->budgetElementLimitFactory->create(
                    $element,
                    $amount,
                    $period
                );
            } else {
                $elementLimit->updateAmount($amount);
            }
            $this->budgetElementLimitRepository->save([$elementLimit]);
        }
    }
}
