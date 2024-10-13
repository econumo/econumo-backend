<?php

declare(strict_types=1);


namespace App\EconumoOneBundle\Domain\Service;


use App\EconumoOneBundle\Domain\Entity\Account;
use App\EconumoOneBundle\Domain\Entity\AccountAccessInvite;
use App\EconumoOneBundle\Domain\Entity\ValueObject\AccountUserRole;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Email;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;
use App\EconumoOneBundle\Domain\Exception\AccountAccessException;
use App\EconumoOneBundle\Domain\Exception\DomainException;
use App\EconumoOneBundle\Domain\Exception\NotFoundException;
use App\EconumoOneBundle\Domain\Factory\AccountAccessFactoryInterface;
use App\EconumoOneBundle\Domain\Factory\AccountAccessInviteFactoryInterface;
use App\EconumoOneBundle\Domain\Factory\AccountOptionsFactoryInterface;
use App\EconumoOneBundle\Domain\Repository\AccountAccessInviteRepositoryInterface;
use App\EconumoOneBundle\Domain\Repository\AccountAccessRepositoryInterface;
use App\EconumoOneBundle\Domain\Repository\AccountOptionsRepositoryInterface;
use App\EconumoOneBundle\Domain\Repository\AccountRepositoryInterface;
use App\EconumoOneBundle\Domain\Repository\UserRepositoryInterface;
use App\EconumoOneBundle\Domain\Service\AccountAccessInviteServiceInterface;
use App\EconumoOneBundle\Domain\Service\AntiCorruptionServiceInterface;

class AccountAccessInviteService implements AccountAccessInviteServiceInterface
{
    public function __construct(private readonly UserRepositoryInterface $userRepository, private readonly AccountAccessInviteRepositoryInterface $accountAccessInviteRepository, private readonly AccountAccessInviteFactoryInterface $accountAccessInviteFactory, private readonly AccountRepositoryInterface $accountRepository, private readonly AccountAccessFactoryInterface $accountAccessFactory, private readonly AccountAccessRepositoryInterface $accountAccessRepository, private readonly AntiCorruptionServiceInterface $antiCorruptionService, private readonly AccountOptionsFactoryInterface $accountOptionsFactory, private readonly AccountOptionsRepositoryInterface $accountOptionsRepository)
    {
    }

    public function generate(
        Id $userId,
        Id $accountId,
        Email $recipientUsername,
        AccountUserRole $role
    ): AccountAccessInvite {
        $account = $this->accountRepository->get($accountId);
        $recipient = $this->userRepository->getByEmail($recipientUsername);
        if ($userId->isEqual($recipient->getId())) {
            throw new AccountAccessException('Access for yourself is prohibited');
        }

        $this->antiCorruptionService->beginTransaction(__METHOD__);
        try {
            try {
                $oldInvite = $this->accountAccessInviteRepository->get($accountId, $recipient->getId());
                $this->accountAccessInviteRepository->delete($oldInvite);
            } catch (NotFoundException) {
                // do nothing
            }

            $invite = $this->accountAccessInviteFactory->create(
                $accountId,
                $recipient->getId(),
                $account->getUserId(),
                $role
            );
            $this->accountAccessInviteRepository->save([$invite]);
            $this->antiCorruptionService->commit(__METHOD__);
        } catch (\Throwable $throwable) {
            $this->antiCorruptionService->rollback(__METHOD__);
            throw $throwable;
        }

        return $invite;
    }

    public function accept(Id $userId, string $code): Account
    {
        $invite = $this->accountAccessInviteRepository->getByUserAndCode($userId, $code);
        $account = $this->accountRepository->get($invite->getAccountId());
        $this->antiCorruptionService->beginTransaction(__METHOD__);
        try {
            $access = $this->accountAccessFactory->create(
                $account->getId(),
                $userId,
                $invite->getRole()
            );
            $this->accountAccessRepository->save([$access]);
            $this->accountAccessInviteRepository->delete($invite);

            $accountOptions = $this->accountOptionsFactory->create($account->getId(), $userId, 0);
            $this->accountOptionsRepository->save([$accountOptions]);

            $this->antiCorruptionService->commit(__METHOD__);
        } catch (\Throwable $throwable) {
            $this->antiCorruptionService->rollback(__METHOD__);
            throw $throwable;
        }

        return $account;
    }
}
