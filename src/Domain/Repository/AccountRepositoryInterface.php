<?php
declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\Account\Account;
use App\Domain\Entity\ValueObject\Id;

interface AccountRepositoryInterface
{
    /**
     * @param Id $id
     * @return Account[]
     */
    public function findByUserId(Id $id): array;
}
