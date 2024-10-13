<?php

declare(strict_types=1);


namespace App\EconumoOneBundle\Domain\Factory;

use App\EconumoOneBundle\Domain\Entity\AccountAccessInvite;
use App\EconumoOneBundle\Domain\Entity\ValueObject\AccountUserRole;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;

interface AccountAccessInviteFactoryInterface
{
    public function create(Id $accountId, Id $recipientId, Id $ownerId, AccountUserRole $role): AccountAccessInvite;
}
