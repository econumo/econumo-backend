<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Domain\Service\Dto;

use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;

class PlanDataTagDto
{
    public Id $id;

    public Id $currencyId;

    public float $amount;
}
