<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Domain\Factory;

use App\EconumoOneBundle\Domain\Entity\UserPasswordRequest;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;

interface PasswordUserRequestFactoryInterface
{
    public function create(Id $userId): UserPasswordRequest;
}
