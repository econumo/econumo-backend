<?php

declare(strict_types=1);

namespace App\Application\Budget\Assembler;

use App\Application\Budget\Dto\PlanSharedAccessItemResultDto;
use App\Application\User\Assembler\UserIdToDtoResultAssembler;
use App\Domain\Entity\PlanAccess;

readonly class SharedAccessToResultDtoAssembler
{
    public function __construct(private UserIdToDtoResultAssembler $userIdToDtoResultAssembler)
    {
    }

    public function assemble(PlanAccess $planAccess): PlanSharedAccessItemResultDto
    {
        $item = new PlanSharedAccessItemResultDto();
        $item->role = $planAccess->getRole()->getAlias();
        $item->isAccepted = $planAccess->isAccepted() ? 1 : 0;
        $item->user = $this->userIdToDtoResultAssembler->assemble($planAccess->getUserId());
        return $item;
    }
}
