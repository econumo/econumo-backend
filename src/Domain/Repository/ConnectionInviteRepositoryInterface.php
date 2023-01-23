<?php

declare(strict_types=1);


namespace App\Domain\Repository;

use App\Domain\Entity\ConnectionInvite;
use App\Domain\Entity\ValueObject\ConnectionCode;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Exception\NotFoundException;

interface ConnectionInviteRepositoryInterface
{
    /**
     * @param ConnectionInvite[] $items
     */
    public function save(array $items): void;

    public function delete(ConnectionInvite $item): void;

    public function getByUser(Id $userId): ?ConnectionInvite;

    /**
     * @throws NotFoundException
     */
    public function getByCode(ConnectionCode $code): ConnectionInvite;
}
