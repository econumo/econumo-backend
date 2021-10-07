<?php

declare(strict_types=1);


namespace App\Domain\Factory;

use App\Domain\Entity\AccountAccess;
use App\Domain\Entity\ValueObject\AccountRole;
use App\Domain\Entity\ValueObject\Id;

interface AccountAccessFactoryInterface
{
    public function create(Id $accountId, Id $userId, AccountRole $role): AccountAccess;
}
