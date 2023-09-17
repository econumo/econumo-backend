<?php

declare(strict_types=1);

namespace App\Application\Budget;

use App\Application\Budget\Dto\GetPlanListV1RequestDto;
use App\Application\Budget\Dto\GetPlanListV1ResultDto;
use App\Application\Budget\Assembler\GetPlanListV1ResultAssembler;
use App\Application\Exception\ValidationException;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Repository\PlanRepositoryInterface;
use App\Application\Budget\Dto\OrderPlanListV1RequestDto;
use App\Application\Budget\Dto\OrderPlanListV1ResultDto;
use App\Application\Budget\Assembler\OrderPlanListV1ResultAssembler;
use App\Domain\Service\Budget\PlanServiceInterface;
use App\Domain\Service\Translation\TranslationServiceInterface;

readonly class PlanListService
{
    public function __construct(
        private GetPlanListV1ResultAssembler $getPlanListV1ResultAssembler,
        private PlanRepositoryInterface $planRepository,
        private OrderPlanListV1ResultAssembler $orderPlanListV1ResultAssembler,
        private TranslationServiceInterface $translationService,
        private PlanServiceInterface $planService
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

    public function orderPlanList(
        OrderPlanListV1RequestDto $dto,
        Id $userId
    ): OrderPlanListV1ResultDto {
        if ($dto->changes === []) {
            throw new ValidationException($this->translationService->trans('plan.plan_list.empty_list'));
        }

        $this->planService->orderPlans($userId, $dto->changes);
        return $this->orderPlanListV1ResultAssembler->assemble($dto, $userId);
    }
}
