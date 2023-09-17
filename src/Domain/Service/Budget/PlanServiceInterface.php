<?php

declare(strict_types=1);


namespace App\Domain\Service\Budget;


use App\Domain\Entity\Plan;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Entity\ValueObject\PlanName;

interface PlanServiceInterface
{
    public function createPlan(Id $userId, PlanName $name): Plan;
}
