<?php

declare(strict_types=1);

namespace App\EconumoBundle\Application\User\Assembler;

use App\EconumoBundle\Application\User\Dto\UpdateBudgetV1RequestDto;
use App\EconumoBundle\Application\User\Dto\UpdateBudgetV1ResultDto;
use App\EconumoBundle\Application\User\Assembler\CurrentUserToDtoResultAssembler;
use App\EconumoBundle\Domain\Entity\User;

readonly class UpdateBudgetV1ResultAssembler
{
    public function __construct(private CurrentUserToDtoResultAssembler $currentUserToDtoResultAssembler)
    {
    }

    public function assemble(
        UpdateBudgetV1RequestDto $dto,
        User $user
    ): UpdateBudgetV1ResultDto {
        $result = new UpdateBudgetV1ResultDto();
        $result->user = $this->currentUserToDtoResultAssembler->assemble($user);

        return $result;
    }
}
