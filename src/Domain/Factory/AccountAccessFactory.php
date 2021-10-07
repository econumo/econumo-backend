<?php

declare(strict_types=1);

namespace App\Domain\Factory;

use App\Domain\Entity\AccountAccess;
use App\Domain\Entity\ValueObject\AccountRole;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Service\DatetimeServiceInterface;

class AccountAccessFactory implements AccountAccessFactoryInterface
{
    private DatetimeServiceInterface $datetimeService;

    public function __construct(DatetimeServiceInterface $datetimeService)
    {
        $this->datetimeService = $datetimeService;
    }

    public function create(Id $accountId, Id $userId, AccountRole $role): AccountAccess
    {
        return new AccountAccess(
            $accountId,
            $userId,
            $role,
            $this->datetimeService->getCurrentDatetime()
        );
    }
}
