<?php

declare(strict_types=1);

namespace App\Application\User\Assembler;

use App\Application\User\Dto\UserResultDto;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Repository\UserRepositoryInterface;

class UserIdToDtoResultAssembler
{
    public function __construct(private readonly UserRepositoryInterface $userRepository, private readonly UserToDtoResultAssembler $userToDtoResultAssembler)
    {
    }

    public function assemble(Id $userId): UserResultDto
    {
        $user = $this->userRepository->get($userId);
        return $this->userToDtoResultAssembler->assemble($user);
    }
}
