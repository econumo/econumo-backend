<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\UserPasswordRequest;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Entity\ValueObject\UserPasswordRequestCode;

interface UserPasswordRequestRepositoryInterface
{
    public function getNextIdentity(): Id;

    public function getByUserAndCode(Id $userId, UserPasswordRequestCode $code): UserPasswordRequest;

    public function getByUser(Id $userId): UserPasswordRequest;

    /**
     * @param UserPasswordRequest[] $items
     */
    public function save(array $items): void;

    public function removeUserCodes(Id $userId): void;

    public function delete(UserPasswordRequest $item): void;
}
