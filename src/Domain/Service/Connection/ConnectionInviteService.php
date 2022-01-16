<?php

declare(strict_types=1);


namespace App\Domain\Service\Connection;


use App\Domain\Entity\ConnectionInvite;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Factory\ConnectionInviteFactoryInterface;
use App\Domain\Repository\ConnectionInviteRepositoryInterface;
use App\Domain\Repository\UserRepositoryInterface;

class ConnectionInviteService implements ConnectionInviteServiceInterface
{
    private ConnectionInviteFactoryInterface $connectionInviteFactory;
    private ConnectionInviteRepositoryInterface $connectionInviteRepository;
    private UserRepositoryInterface $userRepository;

    public function __construct(
        ConnectionInviteFactoryInterface $connectionInviteFactory,
        ConnectionInviteRepositoryInterface $connectionInviteRepository,
        UserRepositoryInterface $userRepository
    ) {
        $this->connectionInviteFactory = $connectionInviteFactory;
        $this->connectionInviteRepository = $connectionInviteRepository;
        $this->userRepository = $userRepository;
    }

    public function generate(Id $userId): ConnectionInvite
    {
        $connectionInvite = $this->connectionInviteRepository->getByUser($userId);
        if ($connectionInvite === null) {
            $connectionInvite = $this->connectionInviteFactory->create($this->userRepository->getReference($userId));
        }
        $connectionInvite->generateNewCode();
        $this->connectionInviteRepository->save($connectionInvite);
        return $connectionInvite;
    }

    public function delete(Id $userId): void
    {
        $connectionInvite = $this->connectionInviteRepository->getByUser($userId);
        if ($connectionInvite === null) {
            return;
        }
        $connectionInvite->clearCode();
        $this->connectionInviteRepository->save($connectionInvite);
    }
}
