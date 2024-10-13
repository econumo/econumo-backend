<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Domain\Service;

use App\EconumoOneBundle\Domain\Entity\Account;
use App\EconumoOneBundle\Domain\Entity\AccountAccessInvite;
use App\EconumoOneBundle\Domain\Entity\ValueObject\AccountUserRole;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Email;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;

interface AccountAccessInviteServiceInterface
{
    public function generate(Id $userId, Id $accountId, Email $recipientUsername, AccountUserRole $role): AccountAccessInvite;

    public function accept(Id $userId, string $code): Account;
}
