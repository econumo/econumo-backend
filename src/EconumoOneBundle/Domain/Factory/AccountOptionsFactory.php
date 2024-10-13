<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Domain\Factory;

use App\EconumoOneBundle\Domain\Entity\AccountOptions;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;
use App\EconumoOneBundle\Domain\Repository\AccountRepositoryInterface;
use App\EconumoOneBundle\Domain\Repository\UserRepositoryInterface;
use App\EconumoOneBundle\Domain\Service\DatetimeServiceInterface;
use App\EconumoOneBundle\Domain\Factory\AccountOptionsFactoryInterface;

class AccountOptionsFactory implements AccountOptionsFactoryInterface
{
    public function __construct(private readonly AccountRepositoryInterface $accountRepository, private readonly UserRepositoryInterface $userRepository, private readonly DatetimeServiceInterface $datetimeService)
    {
    }

    public function create(
        Id $accountId,
        Id $userId,
        int $position
    ): AccountOptions {
        return new AccountOptions(
            $this->accountRepository->getReference($accountId),
            $this->userRepository->getReference($userId),
            $position,
            $this->datetimeService->getCurrentDatetime()
        );
    }
}
