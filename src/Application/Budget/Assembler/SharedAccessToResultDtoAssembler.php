<?php

declare(strict_types=1);

namespace App\Application\Budget\Assembler;

use App\Application\Budget\Dto\SharedAccessItemResultDto;
use App\Application\User\Assembler\UserIdToDtoResultAssembler;
use App\Domain\Entity\PlanAccess;

readonly class SharedAccessToResultDtoAssembler
{
    public function __construct(private UserIdToDtoResultAssembler $userIdToDtoResultAssembler)
    {
    }

    public function assemble(PlanAccess $planAccess): SharedAccessItemResultDto
    {
        $item = new SharedAccessItemResultDto();
        $item->role = $planAccess->getRole()->getAlias();
        $item->user = $this->userIdToDtoResultAssembler->assemble($planAccess->getUserId());
        return $item;
    }
}
