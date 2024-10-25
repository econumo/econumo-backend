<?php

declare(strict_types=1);


namespace App\EconumoOneBundle\Domain\Factory;


use App\EconumoOneBundle\Domain\Entity\BudgetElementLimit;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;
use DateTimeInterface;

interface BudgetElementLimitFactoryInterface
{
    public function create(
        Id $budgetId,
        Id $elementId,
        float $amount,
        DateTimeInterface $period
    ): BudgetElementLimit;
}
