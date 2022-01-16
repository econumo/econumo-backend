<?php

declare(strict_types=1);


namespace App\Domain\Repository;

use App\Domain\Entity\ConnectionInvite;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Exception\NotFoundException;

interface ConnectionInviteRepositoryInterface
{
    public function save(ConnectionInvite ...$items): void;

    public function delete(ConnectionInvite $item): void;

    /**
     * @param Id $userId
     * @return ConnectionInvite
     * @throws NotFoundException
     */
    public function getByUser(Id $userId): ConnectionInvite;
}
