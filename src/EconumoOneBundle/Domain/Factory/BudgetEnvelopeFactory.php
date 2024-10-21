<?php

declare(strict_types=1);


namespace App\EconumoOneBundle\Domain\Factory;

use App\EconumoOneBundle\Domain\Entity\BudgetEnvelope;
use App\EconumoOneBundle\Domain\Entity\ValueObject\BudgetEnvelopeName;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Icon;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;
use App\EconumoOneBundle\Domain\Repository\BudgetRepositoryInterface;
use App\EconumoOneBundle\Domain\Repository\CategoryRepositoryInterface;
use App\EconumoOneBundle\Domain\Service\DatetimeServiceInterface;

readonly class BudgetEnvelopeFactory implements BudgetEnvelopeFactoryInterface
{
    public function __construct(
        private DatetimeServiceInterface $datetimeService,
        private BudgetRepositoryInterface $budgetRepository,
        private CategoryRepositoryInterface $categoryRepository,
    ) {
    }

    public function create(
        Id $budgetId,
        Id $id,
        BudgetEnvelopeName $name,
        Icon $icon,
        array $categoriesIds
    ): BudgetEnvelope {
        $categories = [];
        foreach ($categoriesIds as $categoryId) {
            $categories[] = $this->categoryRepository->getReference($categoryId);
        }
        return new BudgetEnvelope(
            $id,
            $this->budgetRepository->getReference($budgetId),
            $name,
            $icon,
            $categories,
            $this->datetimeService->getCurrentDatetime()
        );
    }
}
