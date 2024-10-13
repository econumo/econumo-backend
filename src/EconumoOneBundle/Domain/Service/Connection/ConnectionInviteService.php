<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Domain\Service\Connection;

use App\EconumoOneBundle\Domain\Entity\ConnectionInvite;
use App\EconumoOneBundle\Domain\Entity\ValueObject\ConnectionCode;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;
use App\EconumoOneBundle\Domain\Exception\DomainException;
use App\EconumoOneBundle\Domain\Factory\ConnectionInviteFactoryInterface;
use App\EconumoOneBundle\Domain\Repository\ConnectionInviteRepositoryInterface;
use App\EconumoOneBundle\Domain\Repository\UserRepositoryInterface;
use App\EconumoOneBundle\Domain\Service\AntiCorruptionServiceInterface;
use App\EconumoOneBundle\Domain\Service\Connection\ConnectionInviteServiceInterface;

class ConnectionInviteService implements ConnectionInviteServiceInterface
{
    public function __construct(private readonly ConnectionInviteFactoryInterface $connectionInviteFactory, private readonly ConnectionInviteRepositoryInterface $connectionInviteRepository, private readonly UserRepositoryInterface $userRepository, private readonly AntiCorruptionServiceInterface $antiCorruptionService)
    {
    }

    public function generate(Id $userId): ConnectionInvite
    {
        $connectionInvite = $this->connectionInviteRepository->getByUser($userId);
        if (!$connectionInvite instanceof ConnectionInvite) {
            $connectionInvite = $this->connectionInviteFactory->create($this->userRepository->getReference($userId));
        }

        $connectionInvite->generateNewCode();
        $this->connectionInviteRepository->save([$connectionInvite]);
        return $connectionInvite;
    }

    public function delete(Id $userId): void
    {
        $connectionInvite = $this->connectionInviteRepository->getByUser($userId);
        if (!$connectionInvite instanceof ConnectionInvite) {
            return;
        }

        $connectionInvite->clearCode();
        $this->connectionInviteRepository->save([$connectionInvite]);
    }

    public function accept(Id $userId, ConnectionCode $code): void
    {
        $connectionInvite = $this->connectionInviteRepository->getByCode($code);
        if ($connectionInvite->getUserId()->isEqual($userId)) {
            throw new DomainException('Inviting yourself?');
        }

        $this->antiCorruptionService->beginTransaction(__METHOD__);
        try {
            $owner = $this->userRepository->get($connectionInvite->getUserId());
            $guest = $this->userRepository->get($userId);

            $owner->connectUser($guest);
            $guest->connectUser($owner);
            $this->userRepository->save([$owner, $guest]);

            $connectionInvite->clearCode();
            $this->connectionInviteRepository->save([$connectionInvite]);

            $this->antiCorruptionService->commit(__METHOD__);
        } catch (\Throwable $throwable) {
            $this->antiCorruptionService->rollback(__METHOD__);
            throw $throwable;
        }
    }
}
