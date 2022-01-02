<?php

declare(strict_types=1);

namespace App\Domain\Service\User;

use App\Domain\Entity\ValueObject\Id;
use App\Domain\Exception\UserPasswordNotValidException;

interface UserPasswordServiceInterface
{
    public function updatePassword(Id $userId, string $password): void;

    /**
     * @param Id $userId
     * @param string $oldPassword
     * @param string $newPassword
     * @return void
     * @throws UserPasswordNotValidException
     */
    public function changePassword(Id $userId, string $oldPassword, string $newPassword): void;
}
