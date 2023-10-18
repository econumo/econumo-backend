<?php

declare(strict_types=1);

namespace App\Application\Budget;

use App\Application\Budget\Dto\GetDataV1RequestDto;
use App\Application\Budget\Dto\GetDataV1ResultDto;
use App\Application\Budget\Assembler\GetDataV1ResultAssembler;
use App\Application\Exception\AccessDeniedException;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Entity\ValueObject\PlanPeriodType;
use App\Domain\Service\Budget\PlanAccessServiceInterface;
use App\Domain\Service\Budget\PlanServiceInterface;
use DateTimeImmutable;

readonly class DataService
{
    public function __construct(
        private GetDataV1ResultAssembler $getDataV1ResultAssembler,
        private PlanServiceInterface $planService,
        private PlanAccessServiceInterface $planAccessService,
    ) {
    }

    public function getData(
        GetDataV1RequestDto $dto,
        Id $userId
    ): GetDataV1ResultDto {
        $planId = new Id($dto->id);
        if (!$this->planAccessService->canReadPlan($userId, $planId)) {
            throw new AccessDeniedException();
        }
        $data = $this->planService->getPlanData(
            $planId,
            new PlanPeriodType($dto->periodType),
            DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $dto->periodStart),
            $dto->numberOfPeriods
        );
        return $this->getDataV1ResultAssembler->assemble($dto, $data);
    }
}
