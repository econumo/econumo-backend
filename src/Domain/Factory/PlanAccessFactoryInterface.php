<?php

declare(strict_types=1);


namespace App\Domain\Factory;

use App\Domain\Entity\PlanAccess;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Entity\ValueObject\UserRole;

interface PlanAccessFactoryInterface
{
    public function create(Id $planId, Id $userId, UserRole $role): PlanAccess;
}
