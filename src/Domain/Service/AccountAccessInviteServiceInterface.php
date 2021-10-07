<?php

declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\Entity\AccountAccessInvite;
use App\Domain\Entity\ValueObject\AccountRole;
use App\Domain\Entity\ValueObject\Email;
use App\Domain\Entity\ValueObject\Id;

interface AccountAccessInviteServiceInterface
{
    public function generate(Id $accountId, Email $recipientUsername, AccountRole $role): AccountAccessInvite;
}
