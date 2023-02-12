<?php

declare(strict_types=1);

namespace App\Application\User\Assembler;

use App\Application\User\Dto\GetUserDataV1RequestDto;
use App\Application\User\Dto\GetUserDataV1ResultDto;
use App\Domain\Entity\User;

class GetUserDataV1ResultAssembler
{
    public function __construct(private readonly CurrentUserToDtoResultAssembler $currentUserToDtoResultAssembler)
    {
    }

    public function assemble(
        GetUserDataV1RequestDto $dto,
        User $user
    ): GetUserDataV1ResultDto {
        $result = new GetUserDataV1ResultDto();
        $result->user = $this->currentUserToDtoResultAssembler->assemble($user);

        return $result;
    }
}
