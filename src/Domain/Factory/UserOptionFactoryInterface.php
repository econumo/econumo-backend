<?php

declare(strict_types=1);

namespace App\Domain\Factory;

use App\Domain\Entity\User;
use App\Domain\Entity\UserOption;

interface UserOptionFactoryInterface
{
    public function create(User $user, string $name, ?string $value): UserOption;
}
