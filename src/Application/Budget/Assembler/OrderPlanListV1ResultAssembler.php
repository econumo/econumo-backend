<?php

declare(strict_types=1);

namespace App\Application\Budget\Assembler;

use App\Application\Budget\Dto\OrderPlanListV1RequestDto;
use App\Application\Budget\Dto\OrderPlanListV1ResultDto;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Repository\PlanRepositoryInterface;

readonly class OrderPlanListV1ResultAssembler
{
    public function __construct(
        private PlanRepositoryInterface $planRepository,
        private PlanToDtoV1ResultAssembler $planToDtoV1ResultAssembler
    ) {
    }

    public function assemble(
        OrderPlanListV1RequestDto $dto,
        Id $userId
    ): OrderPlanListV1ResultDto {
        $result = new OrderPlanListV1ResultDto();
        $plans = $this->planRepository->getAvailableForUserId($userId);
        $result->items = [];
        foreach ($plans as $plan) {
            $result->items[] = $this->planToDtoV1ResultAssembler->assemble($plan, $userId);
        }

        return $result;
    }
}
