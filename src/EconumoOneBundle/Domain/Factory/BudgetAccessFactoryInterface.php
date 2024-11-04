<?php

declare(strict_types=1);


namespace App\EconumoOneBundle\Domain\Factory;


use App\EconumoOneBundle\Domain\Entity\BudgetAccess;
use App\EconumoOneBundle\Domain\Entity\ValueObject\BudgetUserRole;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;

interface BudgetAccessFactoryInterface
{
    public function create(Id $budgetId, Id $userId, BudgetUserRole $role): BudgetAccess;
}
