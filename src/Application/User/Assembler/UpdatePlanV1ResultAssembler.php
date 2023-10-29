<?php

declare(strict_types=1);

namespace App\Application\User\Assembler;

use App\Application\User\Dto\UpdatePlanV1RequestDto;
use App\Application\User\Dto\UpdatePlanV1ResultDto;
use App\Domain\Entity\User;

readonly class UpdatePlanV1ResultAssembler
{
    public function __construct(private CurrentUserToDtoResultAssembler $currentUserToDtoResultAssembler)
    {
    }

    public function assemble(
        UpdatePlanV1RequestDto $dto,
        User $user
    ): UpdatePlanV1ResultDto {
        $result = new UpdatePlanV1ResultDto();
        $result->user = $this->currentUserToDtoResultAssembler->assemble($user);

        return $result;
    }
}
