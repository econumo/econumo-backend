<?php

declare(strict_types=1);


namespace App\Domain\Factory;

use App\Domain\Entity\AccountAccessInvite;
use App\Domain\Entity\ValueObject\AccountUserRole;
use App\Domain\Entity\ValueObject\Id;

interface AccountAccessInviteFactoryInterface
{
    public function create(Id $accountId, Id $recipientId, Id $ownerId, AccountUserRole $role): AccountAccessInvite;
}
