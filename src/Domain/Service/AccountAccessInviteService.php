<?php

declare(strict_types=1);


namespace App\Domain\Service;


use App\Domain\Entity\Account;
use App\Domain\Entity\AccountAccessInvite;
use App\Domain\Entity\ValueObject\AccountRole;
use App\Domain\Entity\ValueObject\Email;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Factory\AccountAccessFactoryInterface;
use App\Domain\Factory\AccountAccessInviteFactoryInterface;
use App\Domain\Repository\AccountAccessInviteRepositoryInterface;
use App\Domain\Repository\AccountAccessRepositoryInterface;
use App\Domain\Repository\AccountRepositoryInterface;
use App\Domain\Repository\UserRepositoryInterface;

class AccountAccessInviteService implements AccountAccessInviteServiceInterface
{
    private UserRepositoryInterface $userRepository;
    private AccountAccessInviteRepositoryInterface $accountAccessInviteRepository;
    private AccountAccessInviteFactoryInterface $accountAccessInviteFactory;
    private AccountRepositoryInterface $accountRepository;
    private AccountAccessFactoryInterface $accountAccessFactory;
    private AccountAccessRepositoryInterface $accountAccessRepository;

    public function __construct(
        UserRepositoryInterface $userRepository,
        AccountAccessInviteRepositoryInterface $accountAccessInviteRepository,
        AccountAccessInviteFactoryInterface $accountAccessInviteFactory,
        AccountRepositoryInterface $accountRepository,
        AccountAccessFactoryInterface $accountAccessFactory,
        AccountAccessRepositoryInterface $accountAccessRepository
    ) {
        $this->userRepository = $userRepository;
        $this->accountAccessInviteRepository = $accountAccessInviteRepository;
        $this->accountAccessInviteFactory = $accountAccessInviteFactory;
        $this->accountRepository = $accountRepository;
        $this->accountAccessFactory = $accountAccessFactory;
        $this->accountAccessRepository = $accountAccessRepository;
    }

    public function generate(
        Id $accountId,
        Email $recipientUsername,
        AccountRole $role
    ): AccountAccessInvite {
        $account = $this->accountRepository->get($accountId);
        $recipient = $this->userRepository->getByEmail($recipientUsername);
        $oldInvite = $this->accountAccessInviteRepository->get($accountId, $recipient->getId());
        $this->accountAccessInviteRepository->delete($oldInvite);
        $invite = $this->accountAccessInviteFactory->create(
            $accountId,
            $recipient->getId(),
            $account->getUserId(),
            $role
        );
        $this->accountAccessInviteRepository->save($invite);

        return $invite;
    }

    public function accept(Id $userId, string $code): Account
    {
        $invite = $this->accountAccessInviteRepository->getByUserAndCode($userId, $code);
        $account = $this->accountRepository->get($invite->getAccountId());
        $access = $this->accountAccessFactory->create(
            $account->getId(),
            $userId,
            $invite->getRole()
        );
        $this->accountAccessRepository->save($access);
        $this->accountAccessInviteRepository->delete($invite);

        return $account;
    }
}
