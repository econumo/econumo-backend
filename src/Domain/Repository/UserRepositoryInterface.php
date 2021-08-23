<?php
declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\User;
use App\Domain\Entity\ValueObject\Email;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Entity\ValueObject\Identifier;

interface UserRepositoryInterface
{
    public function getNextIdentity(): Id;

    public function loadByIdentifier(Identifier $identifier): User;

    public function getByEmail(Email $email): User;

    public function save(User ...$users): void;

    public function get(Id $id): User;
}
