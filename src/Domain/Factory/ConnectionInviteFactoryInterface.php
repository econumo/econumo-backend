<?php

declare(strict_types=1);


namespace App\Domain\Factory;

use App\Domain\Entity\ConnectionInvite;
use App\Domain\Entity\User;

interface ConnectionInviteFactoryInterface
{
    public function create(User $user): ConnectionInvite;
}
