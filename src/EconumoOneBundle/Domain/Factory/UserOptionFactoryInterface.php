<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Domain\Factory;

use App\EconumoOneBundle\Domain\Entity\User;
use App\EconumoOneBundle\Domain\Entity\UserOption;

interface UserOptionFactoryInterface
{
    public function create(User $user, string $name, ?string $value): UserOption;
}
