<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Factory;

use App\Domain\Entity\User;
use App\Domain\Factory\UserFactoryInterface;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\Service\Datetime\DatetimeServiceInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFactory implements UserFactoryInterface
{
    private UserRepositoryInterface $repository;
    private DatetimeServiceInterface $datetimeService;
    private UserPasswordHasherInterface $userPasswordHasher;

    public function __construct(
        UserRepositoryInterface $repository,
        DatetimeServiceInterface $datetimeService,
        UserPasswordHasherInterface $userPasswordHasher
    ) {
        $this->repository = $repository;
        $this->datetimeService = $datetimeService;
        $this->userPasswordHasher = $userPasswordHasher;
    }

    public function create(string $name, string $email, string $password): User
    {
        $user = new User(
            $this->repository->getNextIdentity(),
            sha1(random_bytes(10)),
            $name,
            $this->datetimeService->getCurrentDatetime()
        );
        $this->repository->secureEmail($user, $email);
        $user->updatePassword($this->userPasswordHasher->hashPassword($user, $password));

        return $user;
    }
}
