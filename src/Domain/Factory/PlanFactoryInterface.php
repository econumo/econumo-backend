<?php

declare(strict_types=1);


namespace App\Domain\Factory;


use App\Domain\Entity\Plan;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Entity\ValueObject\PlanName;

interface PlanFactoryInterface
{
    public function create(Id $userId, PlanName $name): Plan;
}
