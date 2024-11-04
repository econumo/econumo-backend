<?php

declare(strict_types=1);


namespace App\EconumoOneBundle\Domain\Factory;


use App\EconumoOneBundle\Domain\Entity\BudgetElement;
use App\EconumoOneBundle\Domain\Entity\BudgetElementLimit;
use DateTimeInterface;

interface BudgetElementLimitFactoryInterface
{
    public function create(
        BudgetElement $budgetElement,
        float $amount,
        DateTimeInterface $period
    ): BudgetElementLimit;
}
