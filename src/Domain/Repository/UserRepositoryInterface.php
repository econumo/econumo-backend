<?php
declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\User;
use App\Domain\Entity\ValueObject\Id;

interface UserRepositoryInterface
{
    public function getNextIdentity(): Id;

    public function secureEmail(User $user, string $email): void;

    public function loadByEmail(string $email): User;
}
