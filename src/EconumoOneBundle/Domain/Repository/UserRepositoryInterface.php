<?php
declare(strict_types=1);

namespace App\EconumoOneBundle\Domain\Repository;

use App\EconumoOneBundle\Domain\Entity\User;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Email;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Identifier;

interface UserRepositoryInterface
{
    public function getNextIdentity(): Id;

    public function loadByIdentifier(Identifier $identifier): User;

    public function getByEmail(Email $email): User;

    /**
     * @param User[] $users
     */
    public function save(array $users): void;

    public function get(Id $id): User;

    public function getReference(Id $id): User;
}
