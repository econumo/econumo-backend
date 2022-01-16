<?php

declare(strict_types=1);


namespace App\Domain\Service\Connection;

use App\Domain\Entity\ConnectionInvite;
use App\Domain\Entity\ValueObject\ConnectionCode;
use App\Domain\Entity\ValueObject\Id;

interface ConnectionInviteServiceInterface
{
    public function generate(Id $userId): ConnectionInvite;

    public function delete(Id $userId): void;

    public function accept(Id $userId, ConnectionCode $code): void;
}
