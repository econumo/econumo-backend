<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Domain\Factory;

use App\EconumoOneBundle\Domain\Entity\ConnectionInvite;
use App\EconumoOneBundle\Domain\Entity\User;
use App\EconumoOneBundle\Domain\Factory\ConnectionInviteFactoryInterface;

class ConnectionInviteFactory implements ConnectionInviteFactoryInterface
{
    public function create(User $user): ConnectionInvite
    {
        return new ConnectionInvite($user);
    }
}
