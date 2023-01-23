<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\Account;
use App\Domain\Entity\ValueObject\Id;

interface AccountRepositoryInterface
{
    public function getNextIdentity(): Id;

    /**
     * @param Id $userId
     * @return Account[]
     */
    public function getAvailableForUserId(Id $userId): array;

    public function get(Id $id): Account;

    /**
     * @param Account[] $accounts
     * @return void
     */
    public function save(array $accounts): void;

    public function delete(Id $id): void;

    public function getReference(Id $id): Account;
}
