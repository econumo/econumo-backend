<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Domain\Factory;

use App\EconumoOneBundle\Domain\Entity\AccountOptions;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;

interface AccountOptionsFactoryInterface
{
    public function create(
        Id $accountId,
        Id $userId,
        int $position
    ): AccountOptions;
}
