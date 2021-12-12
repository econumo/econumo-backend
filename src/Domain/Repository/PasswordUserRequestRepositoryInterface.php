<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\PasswordUserRequest;
use App\Domain\Entity\ValueObject\Id;

interface PasswordUserRequestRepositoryInterface
{
    public function getNextIdentity(): Id;

    public function getByCode(string $code): PasswordUserRequest;

    public function save(PasswordUserRequest ...$items): void;
}
