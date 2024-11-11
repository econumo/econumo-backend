<?php

declare(strict_types=1);


namespace App\EconumoBundle\Domain\Service\Budget;


use App\EconumoBundle\Domain\Entity\ValueObject\Id;

interface BudgetLimitServiceInterface
{
    public function setLimit(Id $budgetId, Id $elementId, \DateTimeInterface $period, ?float $amount): void;
}
