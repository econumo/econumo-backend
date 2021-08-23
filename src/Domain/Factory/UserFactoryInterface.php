<?php
declare(strict_types=1);

namespace App\Domain\Factory;

use App\Domain\Entity\User;
use App\Domain\Entity\ValueObject\Email;

interface UserFactoryInterface
{
    public function create(string $name, Email $email, string $password): User;
}
