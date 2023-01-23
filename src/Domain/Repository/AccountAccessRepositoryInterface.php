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

    /**
     * @param AccountAccess[] $items
     * @return void
     */
    public function save(array $items): void;

    /**
     * @param Id $accountId
     * @param Id $userId
     * @return AccountAccess
     * @throws NotFoundException
     */
    public function get(Id $accountId, Id $userId): AccountAccess;

    public function delete(AccountAccess $accountAccess): void;

    /**
     * @param Id $userId
     * @return AccountAccess[]
     */
    public function getOwnedByUser(Id $userId): array;

    /**
     * @param Id $userId
     * @return AccountAccess[]
     */
    public function getReceivedAccess(Id $userId): array;

    /**
     * @param Id $userId
     * @return AccountAccess[]
     */
    public function getIssuedAccess(Id $userId): array;
}
