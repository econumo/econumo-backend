<?php

declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\Entity\Account;
use App\Domain\Entity\AccountAccessInvite;
use App\Domain\Entity\ValueObject\AccountUserRole;
use App\Domain\Entity\ValueObject\Email;
use App\Domain\Entity\ValueObject\Id;

interface AccountAccessInviteServiceInterface
{
    public function generate(Id $userId, Id $accountId, Email $recipientUsername, AccountUserRole $role): AccountAccessInvite;

    public function accept(Id $userId, string $code): Account;
}
