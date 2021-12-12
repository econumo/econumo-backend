<?php

declare(strict_types=1);

namespace App\Domain\Factory;

use App\Domain\Entity\PasswordUserRequest;
use App\Domain\Entity\ValueObject\Id;

interface PasswordUserRequestFactoryInterface
{
    public function create(Id $userId): PasswordUserRequest;
}
