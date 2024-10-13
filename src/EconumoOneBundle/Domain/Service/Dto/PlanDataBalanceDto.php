<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Domain\Service\Dto;

use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;

class PlanDataBalanceDto
{
    public Id $currencyId;

    public ?float $startBalance;

    public ?float $endBalance = null;

    public ?float $currentBalance = null;

    public ?float $income = null;

    public ?float $expenses = null;

    public ?float $exchanges = null;

    public ?float $hoards = null;
}
