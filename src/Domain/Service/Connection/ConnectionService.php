<?php

declare(strict_types=1);


namespace App\Domain\Service\Connection;

use App\Domain\Entity\ValueObject\Id;
use App\Domain\Exception\DomainException;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\Service\AccountAccessServiceInterface;
use App\Domain\Service\AntiCorruptionServiceInterface;
use Throwable;

class ConnectionService implements ConnectionServiceInterface
{
    private UserRepositoryInterface $userRepository;
    private AntiCorruptionServiceInterface $antiCorruptionService;
    private ConnectionAccountServiceInterface $connectionAccountService;

    public function __construct(
        UserRepositoryInterface $userRepository,
        AntiCorruptionServiceInterface $antiCorruptionService,
        ConnectionAccountServiceInterface $connectionAccountService
    ) {
        $this->userRepository = $userRepository;
        $this->antiCorruptionService = $antiCorruptionService;
        $this->connectionAccountService = $connectionAccountService;
    }

    public function getUserList(Id $userId): iterable
    {
        $user = $this->userRepository->get($userId);
        return $user->getConnections();
    }

    public function delete(Id $userId, Id $connectedUserId): void
    {
        $initiator = $this->userRepository->get($userId);
        $connectedUser = $this->userRepository->get($connectedUserId);
        if ($initiator->getId()->isEqual($connectedUser->getId())) {
            throw new DomainException('Deleting yourself?');
        }

        $this->antiCorruptionService->beginTransaction();
        try {
            $sharedAccess = $this->connectionAccountService->getSharedAccess($initiator->getId());
            foreach ($sharedAccess as $accountAccess) {
                if ($accountAccess->getAccount()->getUserId()->isEqual($connectedUser->getId())) {
                    $this->connectionAccountService->deleteAccountAccess($initiator->getId(), $accountAccess->getAccountId());
                }
            }

            $initiator->deleteConnection($connectedUser);
            $connectedUser->deleteConnection($initiator);
            $this->userRepository->save($initiator, $connectedUser);

            $this->antiCorruptionService->commit();
        } catch (Throwable $exception) {
            $this->antiCorruptionService->rollback();
            throw $exception;
        }
    }
}
