<?php

declare(strict_types=1);


namespace App\Domain\Factory;

use App\Domain\Entity\AccountAccessInvite;
use App\Domain\Entity\ValueObject\AccountRole;
use App\Domain\Entity\ValueObject\Id;

interface AccountAccessInviteFactoryInterface
{
    public function create(Id $accountId, Id $recipientId, Id $ownerId, AccountRole $role): AccountAccessInvite;
}
