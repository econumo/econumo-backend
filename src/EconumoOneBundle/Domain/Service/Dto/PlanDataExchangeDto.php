<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Domain\Service\Dto;

use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;

class PlanDataExchangeDto
{
    public Id $currencyId;

    public float $budget;

    public float $amount;
}
