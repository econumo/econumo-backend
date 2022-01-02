<?php

declare(strict_types=1);


namespace App\Infrastructure\Symfony\PasswordHasher;


use App\Domain\Entity\ValueObject\Id;
use App\Domain\Exception\UserPasswordNotValidException;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\Service\User\UserPasswordServiceInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserPasswordService implements UserPasswordServiceInterface
{
    private UserPasswordHasherInterface $passwordHasher;
    private UserRepositoryInterface $userRepository;

    public function __construct(UserPasswordHasherInterface $passwordHasher, UserRepositoryInterface $userRepository)
    {
        $this->passwordHasher = $passwordHasher;
        $this->userRepository = $userRepository;
    }

    public function changePassword(Id $userId, string $oldPassword, string $newPassword): void
    {
        $user = $this->userRepository->get($userId);
        if (!$this->passwordHasher->isPasswordValid($user, $oldPassword)) {
            throw new UserPasswordNotValidException();
        }
        $this->updatePassword($userId, $newPassword);
    }

    public function updatePassword(Id $userId, string $password): void
    {
        $user = $this->userRepository->get($userId);
        $user->updatePassword($this->passwordHasher->hashPassword($user, $password));
        $this->userRepository->save($user);
    }
}
