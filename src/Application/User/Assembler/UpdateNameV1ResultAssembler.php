<?php

declare(strict_types=1);

namespace App\Application\User\Assembler;

use App\Application\User\Dto\UpdateNameV1RequestDto;
use App\Application\User\Dto\UpdateNameV1ResultDto;
use App\Domain\Entity\User;

class UpdateNameV1ResultAssembler
{
    public function __construct(private readonly CurrentUserToDtoResultAssembler $currentUserToDtoResultAssembler)
    {
    }

    public function assemble(
        UpdateNameV1RequestDto $dto,
        User $user
    ): UpdateNameV1ResultDto {
        $result = new UpdateNameV1ResultDto();
        $result->user = $this->currentUserToDtoResultAssembler->assemble($user);

        return $result;
    }
}
