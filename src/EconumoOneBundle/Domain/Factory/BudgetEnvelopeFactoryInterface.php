<?php

declare(strict_types=1);


namespace App\EconumoOneBundle\Domain\Factory;

use App\EconumoOneBundle\Domain\Entity\BudgetEnvelope;
use App\EconumoOneBundle\Domain\Entity\ValueObject\BudgetEnvelopeName;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Icon;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;

interface BudgetEnvelopeFactoryInterface
{
    /**
     * @param Id $budgetId
     * @param Id $id
     * @param BudgetEnvelopeName $name
     * @param Icon $icon
     * @param Id[] $categoriesIds
     * @return BudgetEnvelope
     */
    public function create(
        Id $budgetId,
        Id $id,
        BudgetEnvelopeName $name,
        Icon $icon,
        array $categoriesIds
    ): BudgetEnvelope;
}
