<?php

declare(strict_types=1);

namespace App\Domain\Factory;

use App\Domain\Entity\AccountOptions;
use App\Domain\Entity\ValueObject\Id;

interface AccountOptionsFactoryInterface
{
    public function create(
        Id $accountId,
        Id $userId,
        int $position
    ): AccountOptions;
}
