<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Domain\Service\Connection;

use App\EconumoOneBundle\Domain\Entity\User;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;

interface ConnectionServiceInterface
{
    /**
     * @param Id $userId
     * @return User[]
     */
    public function getUserList(Id $userId): iterable;

    public function delete(Id $initiatorUserId, Id $connectedUserId): void;
}
