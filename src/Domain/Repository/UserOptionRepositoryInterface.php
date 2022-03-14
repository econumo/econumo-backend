<?php
declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\UserOption;
use App\Domain\Entity\ValueObject\Id;

interface UserOptionRepositoryInterface
{
    public function getNextIdentity(): Id;

    public function save(UserOption ...$userOptions): void;

    public function delete(UserOption $userOption): void;

    public function getReference(Id $id): UserOption;
}
