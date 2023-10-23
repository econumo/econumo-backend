<?php

declare(strict_types=1);

namespace App\Application\Budget\Assembler;

use App\Application\Budget\Dto\OrderEnvelopeListV1RequestDto;
use App\Application\Budget\Dto\OrderEnvelopeListV1ResultDto;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Repository\EnvelopeRepositoryInterface;

readonly class OrderEnvelopeListV1ResultAssembler
{
    public function __construct(
        private EnvelopeToDtoResultAssembler $envelopeToDtoResultAssembler,
        private EnvelopeRepositoryInterface $envelopeRepository
    ) {
    }

    public function assemble(
        OrderEnvelopeListV1RequestDto $dto,
        Id $planId
    ): OrderEnvelopeListV1ResultDto {
        $result = new OrderEnvelopeListV1ResultDto();
        $result->items = [];
        foreach ($this->envelopeRepository->getByPlanId($planId) as $envelope) {
            $result->items[] = $this->envelopeToDtoResultAssembler->assemble($envelope);
        }

        return $result;
    }
}
