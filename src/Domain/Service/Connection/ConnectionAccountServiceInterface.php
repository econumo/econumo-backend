<?php

declare(strict_types=1);


namespace App\Domain\Service\Connection;


use App\Domain\Entity\AccountAccess;
use App\Domain\Entity\ValueObject\Id;

interface ConnectionAccountServiceInterface
{
    /**
     * @param Id $userId
     * @return AccountAccess[]
     */
    public function getSharedAccess(Id $userId): array;

    public function deleteAccountAccess(Id $userId, Id $sharedAccountId): void;
}
