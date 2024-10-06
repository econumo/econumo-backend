<?php

declare(strict_types=1);

namespace App\EconumoBundle\Domain\Service\Dto;

use App\EconumoBundle\Domain\Entity\ValueObject\Id;

class PlanDataTagDto
{
    public Id $id;

    public Id $currencyId;

    public float $amount;
}
