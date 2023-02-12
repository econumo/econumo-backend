<?php

declare(strict_types=1);

namespace App\Application\User\Assembler;

use App\Application\User\Dto\CurrentUserResultDto;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Repository\UserRepositoryInterface;

class CurrentUserIdToDtoResultAssembler
{
    public function __construct(private readonly UserRepositoryInterface $userRepository, private readonly CurrentUserToDtoResultAssembler $currentUserToDtoResultAssembler)
    {
    }

    public function assemble(Id $userId): CurrentUserResultDto
    {
        $user = $this->userRepository->get($userId);
        return $this->currentUserToDtoResultAssembler->assemble($user);
    }
}
