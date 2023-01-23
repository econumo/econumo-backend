<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\PasswordUserRequest;
use App\Domain\Entity\ValueObject\Id;

interface PasswordUserRequestRepositoryInterface
{
    public function getNextIdentity(): Id;

    public function getByCode(string $code): PasswordUserRequest;

    /**
     * @param PasswordUserRequest[] $items
     * @return void
     */
    public function save(array $items): void;
}
