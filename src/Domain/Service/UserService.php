<?php

declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\Entity\User;
use App\Domain\Entity\ValueObject\Email;
use App\Domain\Exception\NotFoundException;
use App\Domain\Exception\UserRegisteredException;
use App\Domain\Factory\UserFactoryInterface;
use App\Domain\Repository\UserRepositoryInterface;

class UserService implements UserServiceInterface
{
    private UserFactoryInterface $userFactory;
    private UserRepositoryInterface $userRepository;

    public function __construct(
        UserFactoryInterface $userFactory,
        UserRepositoryInterface $userRepository
    ) {
        $this->userFactory = $userFactory;
        $this->userRepository = $userRepository;
    }

    public function register(Email $email, string $password, string $name): User
    {
        try {
            $this->userRepository->getByEmail($email);
            throw new UserRegisteredException();
        } catch (NotFoundException $exception) {
        }

        $user = $this->userFactory->create($name, $email, $password);
        $this->userRepository->save($user);

        return $user;
    }
}
