<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Factory;

use App\Domain\Entity\User;
use App\Domain\Entity\ValueObject\Email;
use App\Domain\Factory\UserFactoryInterface;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\Service\DatetimeServiceInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFactory implements UserFactoryInterface
{
    public function __construct(private readonly UserRepositoryInterface $userRepository, private readonly DatetimeServiceInterface $datetimeService, private readonly UserPasswordHasherInterface $userPasswordHasher)
    {
    }

    public function create(string $name, Email $email, string $password): User
    {
        $user = new User(
            $this->userRepository->getNextIdentity(),
            sha1(random_bytes(10)),
            $name,
            $email,
            $this->datetimeService->getCurrentDatetime()
        );
        $user->updatePassword($this->userPasswordHasher->hashPassword($user, $password));

        return $user;
    }
}
