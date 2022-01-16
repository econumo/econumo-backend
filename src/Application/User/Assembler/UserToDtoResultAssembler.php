<?php

declare(strict_types=1);

namespace App\Application\User\Assembler;

use App\Application\User\Dto\UserResultDto;
use App\Domain\Entity\User;

class UserToDtoResultAssembler
{
    public function assemble(User $user): UserResultDto
    {
        $dto = new UserResultDto();
        $dto->id = $user->getId()->getValue();
        $dto->name = $user->getName();
        $dto->avatar = $user->getAvatarUrl();
        $dto->email = $user->getUserIdentifier();

        return $dto;
    }
}
