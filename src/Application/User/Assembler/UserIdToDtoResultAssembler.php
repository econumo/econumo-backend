<?php

declare(strict_types=1);

namespace App\Application\User\Assembler;

use App\Application\User\Dto\UserResultDto;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Repository\UserRepositoryInterface;

class UserIdToDtoResultAssembler
{
    private UserRepositoryInterface $userRepository;
    private UserToDtoResultAssembler $userToDtoResultAssembler;

    public function __construct(UserRepositoryInterface $userRepository, UserToDtoResultAssembler $userToDtoResultAssembler)
    {
        $this->userRepository = $userRepository;
        $this->userToDtoResultAssembler = $userToDtoResultAssembler;
    }

    public function assemble(Id $userId): UserResultDto
    {
        $user = $this->userRepository->get($userId);
        return $this->userToDtoResultAssembler->assemble($user);
    }
}
