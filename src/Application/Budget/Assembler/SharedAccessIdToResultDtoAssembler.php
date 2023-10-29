<?php

declare(strict_types=1);

namespace App\Application\Budget\Assembler;

use App\Application\Budget\Dto\PlanSharedAccessItemResultDto;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Repository\PlanAccessRepositoryInterface;

readonly class SharedAccessIdToResultDtoAssembler
{
    public function __construct(private PlanAccessRepositoryInterface $planAccessRepository, private SharedAccessToResultDtoAssembler $sharedAccessToResultDtoAssembler)
    {
    }

    public function assemble(Id $planId, Id $userId): PlanSharedAccessItemResultDto
    {
        $planAccess = $this->planAccessRepository->get($planId, $userId);
        return $this->sharedAccessToResultDtoAssembler->assemble($planAccess);
    }
}
