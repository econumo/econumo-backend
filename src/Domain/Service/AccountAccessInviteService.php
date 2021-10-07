<?php

declare(strict_types=1);


namespace App\Domain\Service;


use App\Domain\Entity\AccountAccessInvite;
use App\Domain\Entity\ValueObject\AccountRole;
use App\Domain\Entity\ValueObject\Email;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Factory\AccountAccessInviteFactoryInterface;
use App\Domain\Repository\AccountAccessInviteRepositoryInterface;
use App\Domain\Repository\AccountRepositoryInterface;
use App\Domain\Repository\UserRepositoryInterface;

class AccountAccessInviteService implements AccountAccessInviteServiceInterface
{
    private UserRepositoryInterface $userRepository;
    private AccountAccessInviteRepositoryInterface $accountAccessInviteRepository;
    private AccountAccessInviteFactoryInterface $accountAccessInviteFactory;
    private AccountRepositoryInterface $accountRepository;

    public function __construct(
        UserRepositoryInterface $userRepository,
        AccountAccessInviteRepositoryInterface $accountAccessInviteRepository,
        AccountAccessInviteFactoryInterface $accountAccessInviteFactory,
        AccountRepositoryInterface $accountRepository
    ) {
        $this->userRepository = $userRepository;
        $this->accountAccessInviteRepository = $accountAccessInviteRepository;
        $this->accountAccessInviteFactory = $accountAccessInviteFactory;
        $this->accountRepository = $accountRepository;
    }

    public function generate(
        Id $accountId,
        Email $recipientUsername,
        AccountRole $role
    ): AccountAccessInvite {
        $account = $this->accountRepository->get($accountId);
        $recipient = $this->userRepository->getByEmail($recipientUsername);
        $invite = $this->accountAccessInviteFactory->create(
            $accountId,
            $recipient->getId(),
            $account->getUserId(),
            $role
        );
        $this->accountAccessInviteRepository->save($invite);

        return $invite;
    }
}
