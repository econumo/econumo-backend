<?php

declare(strict_types=1);


namespace App\EconumoBundle\Domain\Factory;


use App\EconumoBundle\Domain\Entity\BudgetElement;
use App\EconumoBundle\Domain\Entity\BudgetElementLimit;
use DateTimeInterface;

interface BudgetElementLimitFactoryInterface
{
    public function create(
        BudgetElement $budgetElement,
        float $amount,
        DateTimeInterface $period
    ): BudgetElementLimit;
}
