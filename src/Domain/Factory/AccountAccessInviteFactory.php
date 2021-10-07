<?php

declare(strict_types=1);


namespace App\Domain\Factory;


use App\Domain\Entity\AccountAccessInvite;
use App\Domain\Entity\ValueObject\AccountRole;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Service\DatetimeServiceInterface;

class AccountAccessInviteFactory implements AccountAccessInviteFactoryInterface
{
    private DatetimeServiceInterface $datetimeService;

    public function __construct(DatetimeServiceInterface $datetimeService)
    {
        $this->datetimeService = $datetimeService;
    }

    public function create(Id $accountId, Id $recipientId, Id $ownerId, AccountRole $role): AccountAccessInvite
    {
        return new AccountAccessInvite(
            $accountId,
            $recipientId,
            $ownerId,
            $role,
            str_pad((string)mt_rand(0, 99999), 5, '0', STR_PAD_LEFT),
            $this->datetimeService->getCurrentDatetime()
        );
    }
}
