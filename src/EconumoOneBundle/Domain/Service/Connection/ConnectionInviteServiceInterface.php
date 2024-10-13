<?php

declare(strict_types=1);


namespace App\EconumoOneBundle\Domain\Service\Connection;

use App\EconumoOneBundle\Domain\Entity\ConnectionInvite;
use App\EconumoOneBundle\Domain\Entity\ValueObject\ConnectionCode;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;

interface ConnectionInviteServiceInterface
{
    public function generate(Id $userId): ConnectionInvite;

    public function delete(Id $userId): void;

    public function accept(Id $userId, ConnectionCode $code): void;
}
