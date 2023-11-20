<?php

declare(strict_types=1);

namespace App\Domain\Factory;

use App\Domain\Entity\UserPasswordRequest;
use App\Domain\Entity\ValueObject\Id;

interface PasswordUserRequestFactoryInterface
{
    public function create(Id $userId): UserPasswordRequest;
}
