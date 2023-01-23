<?php

declare(strict_types=1);


namespace App\Domain\Service\Connection;

use App\Domain\Entity\ValueObject\Id;
use App\Domain\Exception\DomainException;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\Service\AntiCorruptionServiceInterface;
use Throwable;

class ConnectionService implements ConnectionServiceInterface
{
    public function __construct(private readonly UserRepositoryInterface $userRepository, private readonly AntiCorruptionServiceInterface $antiCorruptionService, private readonly ConnectionAccountServiceInterface $connectionAccountService)
    {
    }

    public function getUserList(Id $userId): iterable
    {
        $user = $this->userRepository->get($userId);
        return $user->getConnections();
    }

    public function delete(Id $initiatorUserId, Id $connectedUserId): void
    {
        $initiator = $this->userRepository->get($initiatorUserId);
        $connectedUser = $this->userRepository->get($connectedUserId);
        if ($initiator->getId()->isEqual($connectedUser->getId())) {
            throw new DomainException('Deleting yourself?');
        }

        $this->antiCorruptionService->beginTransaction();
        try {
            foreach ($this->connectionAccountService->getReceivedAccountAccess($initiator->getId()) as $accountAccess) {
                if ($accountAccess->getAccount()->getUserId()->isEqual($connectedUser->getId())) {
                    $this->connectionAccountService->revokeAccountAccess($accountAccess->getUserId(), $accountAccess->getAccountId());
                }
            }

            foreach ($this->connectionAccountService->getIssuedAccountAccess($initiator->getId()) as $accountAccess) {
                if ($accountAccess->getUserId()->isEqual($connectedUser->getId())) {
                    $this->connectionAccountService->revokeAccountAccess($accountAccess->getUserId(), $accountAccess->getAccountId());
                }
            }

            $initiator->deleteConnection($connectedUser);
            $connectedUser->deleteConnection($initiator);
            $this->userRepository->save([$initiator, $connectedUser]);

            $this->antiCorruptionService->commit();
        } catch (Throwable $throwable) {
            $this->antiCorruptionService->rollback();
            throw $throwable;
        }
    }
}
