<?php

declare(strict_types=1);

namespace App\Domain\Factory;

use App\Domain\Entity\ConnectionInvite;
use App\Domain\Entity\User;

class ConnectionInviteFactory implements ConnectionInviteFactoryInterface
{
    public function create(User $user): ConnectionInvite
    {
        return new ConnectionInvite($user);
    }
}
