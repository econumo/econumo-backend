<?php
declare(strict_types=1);

namespace App\Domain\Factory;

use App\Domain\Entity\User;

interface UserFactoryInterface
{
    public function create(string $name, string $email, string $password): User;
}
