<?php

declare(strict_types=1);

namespace App\Application\Budget;

use App\Application\Budget\Dto\GetPlanListV1RequestDto;
use App\Application\Budget\Dto\GetPlanListV1ResultDto;
use App\Application\Budget\Assembler\GetPlanListV1ResultAssembler;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Repository\PlanRepositoryInterface;

readonly class PlanListService
{
    public function __construct(
        private GetPlanListV1ResultAssembler $getPlanListV1ResultAssembler,
        private PlanRepositoryInterface $planRepository
    )
    {
    }

    public function getPlanList(
        GetPlanListV1RequestDto $dto,
        Id $userId
    ): GetPlanListV1ResultDto {
        $plans = $this->planRepository->getAvailableForUserId($userId);
        return $this->getPlanListV1ResultAssembler->assemble($dto, $plans, $userId);
    }
}
