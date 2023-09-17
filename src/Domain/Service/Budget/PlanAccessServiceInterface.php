<?php

declare(strict_types=1);


namespace App\Domain\Service\Budget;

use App\Domain\Entity\ValueObject\Id;

interface PlanAccessServiceInterface
{
    public function canDeletePlan(Id $userId, Id $planId): bool;

    public function canUpdatePlan(Id $userId, Id $planId): bool;
}
