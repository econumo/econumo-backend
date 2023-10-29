<?php

declare(strict_types=1);

namespace App\Domain\Factory;

use App\Domain\Entity\PlanOptions;
use App\Domain\Entity\ValueObject\Id;

interface PlanOptionsFactoryInterface
{
    public function create(
        Id $planId,
        Id $userId,
        int $position
    ): PlanOptions;
}
