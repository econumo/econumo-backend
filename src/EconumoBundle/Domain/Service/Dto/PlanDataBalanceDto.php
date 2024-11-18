<?php

declare(strict_types=1);

namespace App\EconumoBundle\Domain\Service\Dto;

use App\EconumoBundle\Domain\Entity\ValueObject\Id;

class PlanDataBalanceDto
{
    public Id $currencyId;

    public ?float $startBalance = null;

    public ?float $endBalance = null;

    public ?float $currentBalance = null;

    public ?float $income = null;

    public ?float $expenses = null;

    public ?float $exchanges = null;

    public ?float $hoards = null;
}
