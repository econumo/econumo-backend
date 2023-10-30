<?php

declare(strict_types=1);

namespace App\Application\Budget;

use App\Application\Budget\Dto\OrderEnvelopeListV1RequestDto;
use App\Application\Budget\Dto\OrderEnvelopeListV1ResultDto;
use App\Application\Budget\Assembler\OrderEnvelopeListV1ResultAssembler;
use App\Application\Exception\AccessDeniedException;
use App\Application\Exception\ValidationException;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Repository\EnvelopeRepositoryInterface;
use App\Domain\Service\Budget\EnvelopeServiceInterface;
use App\Domain\Service\Budget\PlanAccessServiceInterface;
use App\Domain\Service\Translation\TranslationServiceInterface;

readonly class EnvelopeListService
{
    public function __construct(
        private OrderEnvelopeListV1ResultAssembler $orderEnvelopeListV1ResultAssembler,
        private TranslationServiceInterface $translationService,
        private EnvelopeServiceInterface $envelopeService,
        private EnvelopeRepositoryInterface $envelopeRepository,
        private PlanAccessServiceInterface $planAccessService
    ) {
    }

    public function orderEnvelopeList(
        OrderEnvelopeListV1RequestDto $dto,
        Id $userId
    ): OrderEnvelopeListV1ResultDto {
        if ($dto->changes === []) {
            throw new ValidationException($this->translationService->trans('budget.envelope_list.empty_list'));
        }
        $planId = new Id($dto->planId);
        foreach ($dto->changes as $change) {
            $envelope = $this->envelopeRepository->get(new Id($change->id));
            if (!$envelope->getPlan()->getId()->isEqual($planId)) {
                throw new ValidationException($this->translationService->trans('budget.envelope_list.ordering_error'));
            }
        }
        if (!$this->planAccessService->canUpdatePlan($userId, $planId)) {
            throw new AccessDeniedException();
        }

        $this->envelopeService->orderEnvelopes($planId, $dto->changes);
        return $this->orderEnvelopeListV1ResultAssembler->assemble($dto, $planId);
    }
}
