<?php

declare(strict_types=1);

namespace App\Application\User\Assembler;

use App\Application\User\Dto\LoginUserV1ResultDto;
use App\Domain\Entity\User;

class LoginUserV1ResultAssembler
{
    public function __construct(private readonly CurrentUserToDtoResultAssembler $currentUserToDtoResultAssembler)
    {
    }

    public function assemble(
        User $user,
        string $token
    ): LoginUserV1ResultDto {
        $result = new LoginUserV1ResultDto();
        $result->token = $token;
        $result->user = $this->currentUserToDtoResultAssembler->assemble($user);

        return $result;
    }
}
