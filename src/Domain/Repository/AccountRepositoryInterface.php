<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\Account;
use App\Domain\Entity\ValueObject\Id;

interface AccountRepositoryInterface
{
    public function getNextIdentity(): Id;

    public function save(Account ...$accounts): void;

    /**
     * @param Id $id
     * @return Account[]
     */
    public function findByUserId(Id $id): array;
}
