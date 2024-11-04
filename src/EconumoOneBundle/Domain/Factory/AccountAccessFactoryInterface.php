<?php

declare(strict_types=1);


namespace App\EconumoOneBundle\Domain\Factory;

use App\EconumoOneBundle\Domain\Entity\AccountAccess;
use App\EconumoOneBundle\Domain\Entity\ValueObject\AccountUserRole;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;

interface AccountAccessFactoryInterface
{
    public function create(Id $accountId, Id $userId, AccountUserRole $role): AccountAccess;
}
