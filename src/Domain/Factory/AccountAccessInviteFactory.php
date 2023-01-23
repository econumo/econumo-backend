<?php

declare(strict_types=1);


namespace App\Domain\Factory;


use App\Domain\Entity\AccountAccessInvite;
use App\Domain\Entity\ValueObject\AccountUserRole;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Repository\AccountRepositoryInterface;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\Service\DatetimeServiceInterface;

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
