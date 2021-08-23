<?php

declare(strict_types=1);

namespace App\Application\User\User\Assembler;

use App\Application\User\User\Dto\RegisterUserV1RequestDto;
use App\Application\User\User\Dto\RegisterUserV1ResultDto;
use App\Domain\Entity\User;

class RegisterUserV1ResultAssembler
{
    public function assemble(
        RegisterUserV1RequestDto $dto,
        User $user
    ): RegisterUserV1ResultDto {
        $result = new RegisterUserV1ResultDto();
        $result->id = $user->getId()->getValue();

        return $result;
    }
}
