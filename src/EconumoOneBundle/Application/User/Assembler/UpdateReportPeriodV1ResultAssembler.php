<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Application\User\Assembler;

use App\EconumoOneBundle\Application\User\Assembler\CurrentUserToDtoResultAssembler;
use App\EconumoOneBundle\Application\User\Dto\UpdateReportPeriodV1RequestDto;
use App\EconumoOneBundle\Application\User\Dto\UpdateReportPeriodV1ResultDto;
use App\EconumoOneBundle\Domain\Entity\User;

readonly class UpdateReportPeriodV1ResultAssembler
{
    public function __construct(private CurrentUserToDtoResultAssembler $currentUserToDtoResultAssembler)
    {
    }

    public function assemble(
        UpdateReportPeriodV1RequestDto $dto,
        User $user
    ): UpdateReportPeriodV1ResultDto {
        $result = new UpdateReportPeriodV1ResultDto();
        $result->user = $this->currentUserToDtoResultAssembler->assemble($user);

        return $result;
    }
}
