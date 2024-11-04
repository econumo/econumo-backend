<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Application\User\Assembler;

use App\EconumoOneBundle\Application\User\Dto\UpdateBudgetV1RequestDto;
use App\EconumoOneBundle\Application\User\Dto\UpdateBudgetV1ResultDto;
use App\EconumoOneBundle\Application\User\Assembler\CurrentUserToDtoResultAssembler;
use App\EconumoOneBundle\Domain\Entity\User;

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
