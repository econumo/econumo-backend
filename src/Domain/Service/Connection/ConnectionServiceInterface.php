<?php

declare(strict_types=1);

namespace App\Domain\Service\Connection;

use App\Domain\Entity\User;
use App\Domain\Entity\ValueObject\Id;

interface ConnectionServiceInterface
{
    /**
     * @param Id $userId
     * @return User[]
     */
    public function getUserList(Id $userId): iterable;
}
