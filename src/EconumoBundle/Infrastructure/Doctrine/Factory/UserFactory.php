<?php

declare(strict_types=1);

namespace App\EconumoBundle\Infrastructure\Doctrine\Factory;

use App\EconumoBundle\Domain\Entity\User;
use App\EconumoBundle\Domain\Entity\ValueObject\Email;
use App\EconumoBundle\Domain\Factory\UserFactoryInterface;
use App\EconumoBundle\Domain\Repository\UserRepositoryInterface;
use App\EconumoBundle\Domain\Service\DatetimeServiceInterface;
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
