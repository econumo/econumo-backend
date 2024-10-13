<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Domain\Repository;

use App\EconumoOneBundle\Domain\Entity\AccountOptions;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;
use App\EconumoOneBundle\Domain\Exception\NotFoundException;

interface AccountOptionsRepositoryInterface
{
    /**
     * @return AccountOptions[]
     */
    public function getByUserId(Id $userId): array;

    /**
     * @throws NotFoundException
     */
    public function get(Id $accountId, Id $userId): AccountOptions;

    public function delete(AccountOptions $options): void;

    /**
     * @param AccountOptions[] $accountOptions
     */
    public function save(array $accountOptions): void;
}
