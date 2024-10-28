<?php

declare(strict_types=1);


namespace App\EconumoOneBundle\Domain\Service\Budget;


use App\EconumoOneBundle\Domain\Entity\ValueObject\BudgetUserRole;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;

interface BudgetSharedAccessServiceInterface
{
    public function grantAccess(Id $ownerId, Id $budgetId, Id $invitedUserId, BudgetUserRole $role): void;

    public function acceptAccess(Id $budgetId, Id $invitedUserId): void;

    public function revokeAccess(Id $budgetId, Id $userId): void;
}
