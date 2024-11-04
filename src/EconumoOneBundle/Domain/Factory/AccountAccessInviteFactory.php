<?php

declare(strict_types=1);


namespace App\EconumoOneBundle\Domain\Factory;


use App\EconumoOneBundle\Domain\Entity\AccountAccessInvite;
use App\EconumoOneBundle\Domain\Entity\ValueObject\AccountUserRole;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;
use App\EconumoOneBundle\Domain\Factory\AccountAccessInviteFactoryInterface;
use App\EconumoOneBundle\Domain\Repository\AccountRepositoryInterface;
use App\EconumoOneBundle\Domain\Repository\UserRepositoryInterface;
use App\EconumoOneBundle\Domain\Service\DatetimeServiceInterface;

class AccountAccessInviteFactory implements AccountAccessInviteFactoryInterface
{
    public function __construct(private readonly DatetimeServiceInterface $datetimeService, private readonly AccountRepositoryInterface $accountRepository, private readonly UserRepositoryInterface $userRepository)
    {
    }

    public function create(Id $accountId, Id $recipientId, Id $ownerId, AccountUserRole $role): AccountAccessInvite
    {
        return new AccountAccessInvite(
            $this->accountRepository->getReference($accountId),
            $this->userRepository->getReference($recipientId),
            $this->userRepository->getReference($ownerId),
            $role,
            str_pad((string)random_int(0, 99999), 5, '0', STR_PAD_LEFT),
            $this->datetimeService->getCurrentDatetime()
        );
    }
}
