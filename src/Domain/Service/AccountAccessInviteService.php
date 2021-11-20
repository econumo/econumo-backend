<?php

declare(strict_types=1);


namespace App\Domain\Service;


use App\Domain\Entity\Account;
use App\Domain\Entity\AccountAccessInvite;
use App\Domain\Entity\ValueObject\AccountRole;
use App\Domain\Entity\ValueObject\Email;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Exception\AccountAccessException;
use App\Domain\Exception\DomainException;
use App\Domain\Exception\NotFoundException;
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
    private AntiCorruptionServiceInterface $antiCorruptionService;

    public function __construct(
        UserRepositoryInterface $userRepository,
        AccountAccessInviteRepositoryInterface $accountAccessInviteRepository,
        AccountAccessInviteFactoryInterface $accountAccessInviteFactory,
        AccountRepositoryInterface $accountRepository,
        AccountAccessFactoryInterface $accountAccessFactory,
        AccountAccessRepositoryInterface $accountAccessRepository,
        AntiCorruptionServiceInterface $antiCorruptionService
    ) {
        $this->userRepository = $userRepository;
        $this->accountAccessInviteRepository = $accountAccessInviteRepository;
        $this->accountAccessInviteFactory = $accountAccessInviteFactory;
        $this->accountRepository = $accountRepository;
        $this->accountAccessFactory = $accountAccessFactory;
        $this->accountAccessRepository = $accountAccessRepository;
        $this->antiCorruptionService = $antiCorruptionService;
    }

    public function generate(
        Id $userId,
        Id $accountId,
        Email $recipientUsername,
        AccountRole $role
    ): AccountAccessInvite {
        $account = $this->accountRepository->get($accountId);
        $recipient = $this->userRepository->getByEmail($recipientUsername);
        if ($userId->isEqual($recipient->getId())) {
            throw new AccountAccessException('Access for yourself is prohibited');
        }
        $this->antiCorruptionService->beginTransaction();
        try {
            try {
                $oldInvite = $this->accountAccessInviteRepository->get($accountId, $recipient->getId());
                $this->accountAccessInviteRepository->delete($oldInvite);
            } catch (NotFoundException $exception) {
                // do nothing
            }
            $invite = $this->accountAccessInviteFactory->create(
                $accountId,
                $recipient->getId(),
                $account->getUserId(),
                $role
            );
            $this->accountAccessInviteRepository->save($invite);
            $this->antiCorruptionService->commit();
        } catch (\Throwable $exception) {
            $this->antiCorruptionService->rollback();
            throw $exception;
        }

        return $invite;
    }

    public function accept(Id $userId, string $code): Account
    {
        $invite = $this->accountAccessInviteRepository->getByUserAndCode($userId, $code);
        $account = $this->accountRepository->get($invite->getAccountId());
        $this->antiCorruptionService->beginTransaction();
        try {
            $access = $this->accountAccessFactory->create(
                $account->getId(),
                $userId,
                $invite->getRole()
            );
            $this->accountAccessRepository->save($access);
            $this->accountAccessInviteRepository->delete($invite);
            $this->antiCorruptionService->commit();
        } catch (\Throwable $exception) {
            $this->antiCorruptionService->rollback();
            throw $exception;
        }

        return $account;
    }
}