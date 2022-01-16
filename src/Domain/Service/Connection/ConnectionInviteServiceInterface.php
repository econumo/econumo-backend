<?php

declare(strict_types=1);


namespace App\Domain\Service\Connection;

use App\Domain\Entity\ConnectionInvite;
use App\Domain\Entity\ValueObject\Id;

interface ConnectionInviteServiceInterface
{
    public function generate(Id $userId): ConnectionInvite;
}
