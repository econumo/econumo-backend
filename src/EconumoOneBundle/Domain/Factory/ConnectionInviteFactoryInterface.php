<?php

declare(strict_types=1);


namespace App\EconumoOneBundle\Domain\Factory;

use App\EconumoOneBundle\Domain\Entity\ConnectionInvite;
use App\EconumoOneBundle\Domain\Entity\User;

interface ConnectionInviteFactoryInterface
{
    public function create(User $user): ConnectionInvite;
}
