<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Application\User\Assembler;

use App\EconumoOneBundle\Application\User\Dto\UpdateNameV1RequestDto;
use App\EconumoOneBundle\Application\User\Assembler\CurrentUserToDtoResultAssembler;
use App\EconumoOneBundle\Application\User\Dto\UpdateNameV1ResultDto;
use App\EconumoOneBundle\Domain\Entity\User;

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
