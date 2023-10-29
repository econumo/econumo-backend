<?php

declare(strict_types=1);

namespace App\Domain\Service\Dto;

use App\Domain\Entity\ValueObject\Id;

class PlanDataCategoryDto
{
    public Id $id;

    public Id $currencyId;

    public float $amount;
}
