<?php

declare(strict_types=1);

namespace App\Application\User\Assembler;

use App\Application\User\Dto\UserResultDto;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Repository\UserRepositoryInterface;

class UserIdToDtoResultAssembler
{
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function assemble(Id $userId): UserResultDto
    {
        $user = $this->userRepository->get($userId);
        $dto = new UserResultDto();
        $dto->id = $user->getId()->getValue();
        $dto->name = $user->getName();
        $dto->avatar = $user->getAvatarUrl();
        $dto->email = $user->getUserIdentifier();

        return $dto;
    }
}
