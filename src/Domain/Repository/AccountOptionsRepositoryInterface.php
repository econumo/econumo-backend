<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\AccountOptions;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Exception\NotFoundException;

interface AccountOptionsRepositoryInterface
{
    /**
     * @param Id $userId
     * @return AccountOptions[]
     */
    public function getByUserId(Id $userId): array;

    /**
     * @param Id $accountId
     * @param Id $userId
     * @return AccountOptions
     * @throws NotFoundException
     */
    public function get(Id $accountId, Id $userId): AccountOptions;

    public function save(AccountOptions ...$accountOptions): void;
}
