<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Application\User\Assembler;

use App\EconumoOneBundle\Application\User\Assembler\CurrentUserToDtoResultAssembler;
use App\EconumoOneBundle\Application\User\Dto\RegisterUserV1RequestDto;
use App\EconumoOneBundle\Application\User\Dto\RegisterUserV1ResultDto;
use App\EconumoOneBundle\Domain\Entity\User;

class RegisterUserV1ResultAssembler
{
    public function __construct(private readonly CurrentUserToDtoResultAssembler $currentUserToDtoResultAssembler)
    {
    }

    public function assemble(
        RegisterUserV1RequestDto $dto,
        User $user
    ): RegisterUserV1ResultDto {
        $result = new RegisterUserV1ResultDto();
        $result->user = $this->currentUserToDtoResultAssembler->assemble($user);

        return $result;
    }
}
