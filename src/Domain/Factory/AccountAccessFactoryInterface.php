<?php

declare(strict_types=1);


namespace App\Domain\Factory;

use App\Domain\Entity\AccountAccess;
use App\Domain\Entity\ValueObject\AccountUserRole;
use App\Domain\Entity\ValueObject\Id;

interface AccountAccessFactoryInterface
{
    public function create(Id $accountId, Id $userId, AccountUserRole $role): AccountAccess;
}
