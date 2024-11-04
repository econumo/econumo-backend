<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Infrastructure\Doctrine\Factory;

use App\EconumoOneBundle\Domain\Entity\User;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Email;
use App\EconumoOneBundle\Domain\Factory\UserFactoryInterface;
use App\EconumoOneBundle\Domain\Repository\UserRepositoryInterface;
use App\EconumoOneBundle\Domain\Service\DatetimeServiceInterface;
use App\EconumoOneBundle\Domain\Service\EncodeServiceInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

readonly class UserFactory implements UserFactoryInterface
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private DatetimeServiceInterface $datetimeService,
        private UserPasswordHasherInterface $userPasswordHasher,
        private EncodeServiceInterface $encodeService
    ) {
    }

    public function create(string $name, Email $email, string $password): User
    {
        $identifier = $this->encodeService->hash($email->getValue());
        $encodedEmail = $this->encodeService->encode($email->getValue());
        $avatarUrl = sprintf('https://www.gravatar.com/avatar/%s', md5($email->getValue()));
        $user = new User(
            $this->userRepository->getNextIdentity(),
            $identifier,
            sha1(random_bytes(10)),
            $encodedEmail,
            $name,
            $avatarUrl,
            $this->datetimeService->getCurrentDatetime()
        );
        $user->updatePassword($this->userPasswordHasher->hashPassword($user, $password));

        return $user;
    }
}
