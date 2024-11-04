<?php

declare(strict_types=1);


namespace App\EconumoOneBundle\Domain\Repository;

use App\EconumoOneBundle\Domain\Entity\ConnectionInvite;
use App\EconumoOneBundle\Domain\Entity\ValueObject\ConnectionCode;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;
use App\EconumoOneBundle\Domain\Exception\NotFoundException;

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
