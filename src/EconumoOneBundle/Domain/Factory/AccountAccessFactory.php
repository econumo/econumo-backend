<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Domain\Factory;

use App\EconumoOneBundle\Domain\Entity\AccountAccess;
use App\EconumoOneBundle\Domain\Entity\ValueObject\AccountUserRole;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;
use App\EconumoOneBundle\Domain\Factory\AccountAccessFactoryInterface;
use App\EconumoOneBundle\Domain\Repository\AccountRepositoryInterface;
use App\EconumoOneBundle\Domain\Repository\UserRepositoryInterface;
use App\EconumoOneBundle\Domain\Service\DatetimeServiceInterface;

class AccountAccessFactory implements AccountAccessFactoryInterface
{
    public function __construct(private readonly DatetimeServiceInterface $datetimeService, private readonly AccountRepositoryInterface $accountRepository, private readonly UserRepositoryInterface $userRepository)
    {
    }

    public function create(Id $accountId, Id $userId, AccountUserRole $role): AccountAccess
    {
        return new AccountAccess(
            $this->accountRepository->getReference($accountId),
            $this->userRepository->getReference($userId),
            $role,
            $this->datetimeService->getCurrentDatetime()
        );
    }
}
