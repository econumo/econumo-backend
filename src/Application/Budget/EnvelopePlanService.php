<?php

declare(strict_types=1);

namespace App\Application\Budget;

use App\Application\Budget\Dto\UpdateEnvelopePlanV1RequestDto;
use App\Application\Budget\Dto\UpdateEnvelopePlanV1ResultDto;
use App\Application\Budget\Assembler\UpdateEnvelopePlanV1ResultAssembler;
use App\Application\Exception\AccessDeniedException;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Entity\ValueObject\PlanPeriodType;
use App\Domain\Repository\EnvelopeRepositoryInterface;
use App\Domain\Service\Budget\EnvelopeServiceInterface;
use App\Domain\Service\Budget\PlanAccessServiceInterface;
use App\Domain\Service\Budget\PlanServiceInterface;
use DateTimeImmutable;
use App\Application\Budget\Dto\TransferEnvelopePlanV1RequestDto;
use App\Application\Budget\Dto\TransferEnvelopePlanV1ResultDto;
use App\Application\Budget\Assembler\TransferEnvelopePlanV1ResultAssembler;
use App\Application\Budget\Dto\CopyEnvelopePlanV1RequestDto;
use App\Application\Budget\Dto\CopyEnvelopePlanV1ResultDto;
use App\Application\Budget\Assembler\CopyEnvelopePlanV1ResultAssembler;

readonly class EnvelopePlanService
{
    public function __construct(
        private UpdateEnvelopePlanV1ResultAssembler $updateEnvelopePlanV1ResultAssembler,
        private PlanAccessServiceInterface $planAccessService,
        private EnvelopeRepositoryInterface $envelopeRepository,
        private EnvelopeServiceInterface $envelopeService,
        private TransferEnvelopePlanV1ResultAssembler $transferEnvelopePlanV1ResultAssembler,
        private CopyEnvelopePlanV1ResultAssembler $copyEnvelopePlanV1ResultAssembler,
        private PlanServiceInterface $planService
    ) {
    }

    public function updateEnvelopePlan(
        UpdateEnvelopePlanV1RequestDto $dto,
        Id $userId
    ): UpdateEnvelopePlanV1ResultDto {
        $envelopeId = new Id($dto->envelopeId);
        $envelope = $this->envelopeRepository->get($envelopeId);
        if (!$this->planAccessService->canUpdatePlan($userId, $envelope->getPlan()->getId())) {
            throw new AccessDeniedException();
        }

        $this->envelopeService->updateEnvelopeBudget(
            $envelopeId,
            DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $dto->period),
            $dto->amount
        );
        return $this->updateEnvelopePlanV1ResultAssembler->assemble($dto);
    }

    public function transferEnvelopePlan(
        TransferEnvelopePlanV1RequestDto $dto,
        Id $userId
    ): TransferEnvelopePlanV1ResultDto {
        $fromEnvelopeId = new Id($dto->fromEnvelopeId);
        $toEnvelopeId = new Id($dto->toEnvelopeId);
        $fromEnvelope = $this->envelopeRepository->get($fromEnvelopeId);
        if (!$this->planAccessService->canUpdatePlan($userId, $fromEnvelope->getPlan()->getId())) {
            throw new AccessDeniedException();
        }
        $toEnvelope = $this->envelopeRepository->get($toEnvelopeId);
        if (!$fromEnvelope->getPlan()->getId()->isEqual($toEnvelope->getPlan()->getId())) {
            throw new AccessDeniedException();
        }

        $this->envelopeService->transferEnvelopeBudget(
            $fromEnvelopeId,
            $toEnvelopeId,
            DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $dto->period),
            $dto->amount
        );
        return $this->transferEnvelopePlanV1ResultAssembler->assemble($dto);
    }

    public function copyEnvelopePlan(
        CopyEnvelopePlanV1RequestDto $dto,
        Id $userId
    ): CopyEnvelopePlanV1ResultDto {
        $planId = new Id($dto->planId);
        if (!$this->planAccessService->canUpdatePlan($userId, $planId)) {
            throw new AccessDeniedException();
        }

        $fromPeriod = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $dto->fromPeriod);
        $toPeriod = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $dto->toPeriod);
        $this->envelopeService->copyEnvelopePlan($planId, $fromPeriod, $toPeriod);

        $data = $this->planService->getPlanData(
            $planId,
            new PlanPeriodType(PlanPeriodType::MONTHLY),
            $toPeriod,
            1
        );
        return $this->copyEnvelopePlanV1ResultAssembler->assemble($dto, $data[0]);
    }
}
