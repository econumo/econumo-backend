<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\AccountAccess;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Exception\NotFoundException;

interface AccountAccessRepositoryInterface
{
    /**
     * @param Id $accountId
     * @return AccountAccess[]
     */
    public function getByAccount(Id $accountId): array;

    public function save(AccountAccess ...$items): void;

    /**
     * @param Id $accountId
     * @param Id $userId
     * @return AccountAccess
     * @throws NotFoundException
     */
    public function get(Id $accountId, Id $userId): AccountAccess;

    public function delete(Id $accountId, Id $userId): void;

    /**
     * @param Id $userId
     * @return AccountAccess[]
     */
    public function getOwnedByUser(Id $userId): array;

    /**
     * @param Id $userId
     * @return AccountAccess[]
     */
    public function getSharedAccessForUser(Id $userId): array;
}
