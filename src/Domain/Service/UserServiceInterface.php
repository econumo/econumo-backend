<?php
declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\Entity\User;
use App\Domain\Entity\ValueObject\Email;

interface UserServiceInterface
{
    public function register(Email $email, string $password, string $name): User;
}
